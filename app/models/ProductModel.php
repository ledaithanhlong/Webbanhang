<?php
class ProductModel
{
    private $conn;
    private $table_name = "product";
    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function getProducts()
    {
        $query = "SELECT p.id, p.name, p.description, p.price, p.image, c.name as category_name
FROM " . $this->table_name . " p
LEFT JOIN category c ON p.category_id = c.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
    public function getProductById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }
    public function addProduct($name, $description, $price, $category_id, $image)
    {
        $errors = [];

        // Loại bỏ dấu cách ra khỏi giá để kiểm tra
        $raw_price = $price;
        $price = str_replace(' ', '', $price);

        if (empty($name)) {
            $errors['name'] = 'Tên sản phẩm không được để trống';
        }
        if (empty($description)) {
            $errors['description'] = 'Mô tả không được để trống';
        }
        if (!is_numeric($price) || $price < 0) {
            $errors['price'] = 'Giá sản phẩm không hợp lệ';
        }

        if (count($errors) > 0) {
            return $errors;
        }

        $query = "INSERT INTO " . $this->table_name . " (name, description, price, category_id, image)
                VALUES (:name, :description, :price, :category_id, :image)";
        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));
        $category_id = htmlspecialchars(strip_tags($category_id));
        $image = htmlspecialchars(strip_tags($image));

        // Bind các giá trị
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':image', $image);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function updateProduct($id, $name, $description, $price, $category_id, $image)
    {
        try {
            // Thêm dấu phẩy trước image=:image
            $query = "UPDATE " . $this->table_name . " SET name=:name, description=:description, price=:price, category_id=:category_id, image=:image WHERE id=:id";
            $stmt = $this->conn->prepare($query);

            // Xử lý dữ liệu đầu vào
            $name = htmlspecialchars(strip_tags($name));
            $description = htmlspecialchars(strip_tags($description));
            $price = htmlspecialchars(strip_tags($price));
            $category_id = htmlspecialchars(strip_tags($category_id));
            $image = empty($image) ? 'default.jpg' : htmlspecialchars(strip_tags($image));

            // Bind các tham số
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':image', $image);

            // Thực thi query
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating product: " . $e->getMessage());
            return false;
        }
    }
    public function deleteProduct($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function searchByNameOrCategory($keyword) {
        $sql = "SELECT p.* FROM product p
                LEFT JOIN category c ON p.category_id = c.id
                WHERE p.name LIKE :keyword OR c.name LIKE :keyword";
        $stmt = $this->conn->prepare($sql); // sửa chỗ này từ $this->db -> $this->conn
        $keyword = '%' . $keyword . '%';
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

}
