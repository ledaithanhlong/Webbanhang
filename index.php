<?php
session_start();

// Autoload các file cần thiết
require_once 'app/models/ProductModel.php';
require_once 'app/helpers/SessionHelper.php';

// Lấy URL và xử lý
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/'); // Loại bỏ dấu '/' ở cuối
$url = filter_var($url, FILTER_SANITIZE_URL); // Lọc URL để đảm bảo an toàn
$urlParts = explode('/', $url); // Chia URL thành mảng, ví dụ: /category/add => ['category', 'add']

// Xác định controller và action
$controllerName = !empty($urlParts[0]) ? ucfirst($urlParts[0]) . 'Controller' : 'ProductController'; // Ví dụ: category => CategoryController
$action = $urlParts[1] ?? 'index'; // Ví dụ: add, store, hoặc mặc định là index

// Kiểm tra file controller có tồn tại
$controllerPath = 'app/controllers/' . $controllerName . '.php';
if (!file_exists($controllerPath)) {
    http_response_code(404);
    echo "❌ Không tìm thấy controller: <strong>$controllerName</strong>";
    exit;
}

require_once $controllerPath;

// Khởi tạo controller
$controller = new $controllerName();

// Kiểm tra hàm action có tồn tại
if (!method_exists($controller, $action)) {
    http_response_code(404);
    echo "❌ Không tìm thấy action: <strong>$action</strong> trong controller <strong>$controllerName</strong>";
    exit;
}

// ✅ Nếu là admin controller thì bắt buộc đăng nhập và là admin
if ($controllerName === 'AdminController' && !SessionHelper::isAdmin()) {
    header('Location: /webbanhang/account/login');
    exit;
}

if ($controller == '404') {
    include 'app/views/shares/404error.php';
    exit;
}

// Gọi action kèm theo các tham số (nếu có)
call_user_func_array([$controller, $action], array_slice($urlParts, 2));