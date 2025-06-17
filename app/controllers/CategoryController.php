<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');

class CategoryController
{
    private $categoryModel;

    public function __construct()
    {
        $db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($db);
    }

    public function list()
    {
        $category = $this->categoryModel->getCategory();
        include 'app/views/category/list.php';
    }

    public function add()
    {
        if ($_SESSION['role'] !== 'admin') {
            header("Location: /webbanhang/category/list");
            exit;
        }
        include 'app/views/category/add.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $this->categoryModel->insertCategory($name, $description);
            header('Location: /webbanhang/category/list');
        }
    }

    public function edit($id)
    {
        if ($_SESSION['role'] !== 'admin') {
            header("Location: /webbanhang/category/list");
            exit;
        }

        // Load dữ liệu và hiển thị view sửa (nếu bạn đã có view)
        // Ví dụ:
        $category = $this->categoryModel->getCategoryById($id);
        include 'app/views/category/edit.php';
    }

    public function delete($id)
    {
        if ($_SESSION['role'] !== 'admin') {
            header("Location: /webbanhang/category/list");
            exit;
        }

        $this->categoryModel->deleteCategory($id);
        header("Location: /webbanhang/category/list");
    }

    public function update($id)
    {
        if ($_SESSION['role'] !== 'admin') {
            header("Location: /webbanhang/category/list");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $this->categoryModel->updateCategory($id, $name, $description);
            header("Location: /webbanhang/category/list");
        }
    }

}
