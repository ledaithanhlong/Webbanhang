<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');

class ProductController
{
    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    private function isAdmin()
    {
        return SessionHelper::isAdmin();
    }

    private function isUser()
    {
        return SessionHelper::isUser();
    }

    public function index()
    {
        $products = $this->productModel->getProducts();
        include 'app/views/product/list.php';
    }

    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function add()
    {
        if (!$this->isAdmin()) {
            echo "Bạn không có quyền truy cập chức năng này!";
            exit;
        }
        $categories = (new CategoryModel($this->db))->getCategories();
        include_once 'app/views/product/add.php';
    }

    public function save()
    {
        if (!$this->isAdmin()) {
            echo "Bạn không có quyền truy cập chức năng này!";
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            $image = (isset($_FILES['image']) && $_FILES['image']['error'] == 0)
                ? $this->uploadImage($_FILES['image'])
                : "";
            $result = $this->productModel->addProduct(
                $name,
                $description,
                $price,
                $category_id,
                $image
            );
            if (is_array($result)) {
                $errors = $result;
                $categories = (new CategoryModel($this->db))->getCategories();
                include 'app/views/product/add.php';
            } else {
                header('Location: /webbanhang/Product');
            }
        }
    }

    public function edit($id)
    {
        if (!$this->isAdmin()) {
            echo "Bạn không có quyền truy cập chức năng này!";
            exit;
        }

        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategory(); // ✅ sửa từ $category → $categories

        if ($product) {
            include 'app/views/product/edit.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function update()
    {
        if (!$this->isAdmin()) {
            echo "Bạn không có quyền truy cập chức năng này!";
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];
            $image = (isset($_FILES['image']) && $_FILES['image']['error'] == 0)
                ? $this->uploadImage($_FILES['image'])
                : $_POST['existing_image'];
            $edit = $this->productModel->updateProduct(
                $id,
                $name,
                $description,
                $price,
                $category_id,
                $image
            );
            if ($edit) {
                header('Location: /webbanhang/Product');
            } else {
                echo "Đã xảy ra lỗi khi lưu sản phẩm.";
            }
        }
    }

    public function delete($id)
    {
        if (!$this->isAdmin()) {
            echo "Bạn không có quyền truy cập chức năng này!";
            exit;
        }
        if ($this->productModel->deleteProduct($id)) {
            header('Location: /webbanhang/Product');
        } else {
            echo "Đã xảy ra lỗi khi xóa sản phẩm.";
        }
    }

    private function uploadImage($file)
    {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $imageFileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $target_file = $target_dir . basename($file['name']);

        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            throw new Exception("File không phải là hình ảnh.");
        }

        if ($file['size'] > 10 * 1024 * 1024) {
            throw new Exception("Hình ảnh có kích thước quá lớn.");
        }

        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            throw new Exception("Chỉ cho phép các định dạng JPG, JPEG, PNG, WEBP và GIF.");
        }

        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception("Có lỗi xảy ra khi tải lên hình ảnh.");
        }

        return $target_file;
    }

    public function addToCart($id)
    {
        // if (!$this->isUser()) {
        //     echo "Bạn không có quyền truy cập chức năng này!";
        //     exit;
        // }

        $product = $this->productModel->getProductById($id);
        if (!$product) {
            echo "Không tìm thấy sản phẩm.";
            return;
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image
            ];
        }
        header('Location: /webbanhang/Product/cart');
    }

    public function cart()
    {
        $cart = $_SESSION['cart'] ?? [];
        include 'app/views/product/cart.php';
    }

    public function checkout()
    {
        $cart = $_SESSION['cart'] ?? [];
        include 'app/views/product/checkout.php';
    }

    public function processCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'] ?? 'Chưa cung cấp';
            $addressDetail = $_POST['address'];
            $provinceCode = $_POST['province'];
            $districtCode = $_POST['district'];
            $wardCode = $_POST['ward'];

            // Lấy tên tỉnh/thành, quận/huyện, phường/xã từ API
            $provinceData = json_decode(file_get_contents("https://provinces.open-api.vn/api/p/{$provinceCode}"), true);
            $districtData = json_decode(file_get_contents("https://provinces.open-api.vn/api/d/{$districtCode}"), true);
            $wardData = json_decode(file_get_contents("https://provinces.open-api.vn/api/w/{$wardCode}"), true);

            $fullAddress = $addressDetail;
            if ($wardData && $districtData && $provinceData) {
                $fullAddress .= ', ' . $wardData['name'] . ', ' . $districtData['name'] . ', ' . $provinceData['name'];
            }

            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                echo "Giỏ hàng trống.";
                return;
            }

            $this->db->beginTransaction();
            try {
                // Ghi đơn hàng
                $query = "INSERT INTO orders (name, phone, address) VALUES (:name, :phone, :address)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':address', $fullAddress);
                $stmt->execute();
                $order_id = $this->db->lastInsertId();

                $_SESSION['last_order_id'] = $order_id;

                // Ghi chi tiết đơn hàng
                $cart = $_SESSION['cart'];
                foreach ($cart as $product_id => $item) {
                    $query = "INSERT INTO order_details (order_id, product_id, quantity, price) 
                            VALUES (:order_id, :product_id, :quantity, :price)";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':order_id', $order_id);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':quantity', $item['quantity']);
                    $stmt->bindParam(':price', $item['price']);
                    $stmt->execute();
                }

                // Lưu thông tin khách hàng vào session
                $_SESSION['customer'] = [
                    'name' => $name,
                    'phone' => $phone,
                    'email' => $email,
                    'address' => $fullAddress
                ];

                unset($_SESSION['cart']); // Xóa giỏ hàng
                $this->db->commit();

                header('Location: /webbanhang/Product/orderConfirmation');
            } catch (Exception $e) {
                $this->db->rollBack();
                echo "Đã xảy ra lỗi khi xử lý đơn hàng: " . $e->getMessage();
            }
        }
    }


    public function orderConfirmation()
    {
        if (!isset($_SESSION['last_order_id'])) {
            echo "Không tìm thấy thông tin đơn hàng.";
            return;
        }

        $orderId = $_SESSION['last_order_id'];

        $query = "SELECT od.*, p.name AS product_name, p.image 
                  FROM order_details od
                  JOIN product p ON od.product_id = p.id
                  WHERE od.order_id = :order_id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':order_id', $orderId);
        $stmt->execute();
        $orderDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Lấy thông tin khách hàng từ session hoặc database
        $customer = $_SESSION['customer'] ?? null;

        include 'app/views/product/orderConfirmation.php';
    }

    public function updateCartQuantity($id)
    {
        if (!isset($_SESSION['cart'][$id])) {
            echo "Sản phẩm không tồn tại trong giỏ hàng.";
            return;
        }

        $action = $_POST['action'] ?? '';
        if ($action === 'increase') {
            $_SESSION['cart'][$id]['quantity']++;
        } elseif ($action === 'decrease') {
            $_SESSION['cart'][$id]['quantity']--;
            if ($_SESSION['cart'][$id]['quantity'] <= 0) {
                unset($_SESSION['cart'][$id]);
            }
        }

        header('Location: /webbanhang/Product/cart');
    }

    public function clearCart()
    {
        if (isset($_SESSION['cart'])) {
            unset($_SESSION['cart']);
        }
        header('Location: /webbanhang/Product/cart');
    }

    public function search() {
        $query = $_GET['q'] ?? '';
        $results = $this->productModel->searchByNameOrCategory($query);
        include 'app/views/product/search_result.php';
    }

}
