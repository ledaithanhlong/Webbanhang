<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');
require_once('app/helpers/SessionHelper.php');

class AccountController
{
    private $accountModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
    }

    // Hiển thị trang đăng ký
    public function register()
    {
        include_once 'app/views/account/register.php';
    }

    // Hiển thị trang đăng nhập
    public function login()
    {
        include_once 'app/views/account/login.php';
    }

    // Xử lý đăng ký
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username'] ?? '');
            $fullName = trim($_POST['fullname'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';
            $role = $_POST['role'] ?? 'user';
            $errors = [];

            // Kiểm tra dữ liệu đầu vào
            if (empty($username)) $errors['username'] = "Vui lòng nhập tên đăng nhập!";
            if (empty($fullName)) $errors['fullname'] = "Vui lòng nhập họ tên!";
            if (empty($password)) $errors['password'] = "Vui lòng nhập mật khẩu!";
            if ($password !== $confirmPassword) $errors['confirmPass'] = "Mật khẩu xác nhận không khớp!";
            if (!in_array($role, ['admin', 'user'])) $role = 'user';

            // Kiểm tra tài khoản đã tồn tại
            if ($this->accountModel->getAccountByUsername($username)) {
                $errors['account'] = "Tài khoản này đã được đăng ký!";
            }

            // Nếu có lỗi, hiển thị lại form
            if (!empty($errors)) {
                include_once 'app/views/account/register.php';
            } else {
                // Lưu tài khoản mới
                $result = $this->accountModel->save($username, $fullName, $password, $role);
                if ($result) {
                    header('Location: /webbanhang/account/login');
                    exit;
                } else {
                    $errors['save'] = "Đã xảy ra lỗi khi lưu tài khoản.";
                    include_once 'app/views/account/register.php';
                }
            }
        }
    }

    // Đăng xuất
    public function logout()
    {
        SessionHelper::logout();
        header('Location: /webbanhang/product');
        exit;
    }

    // Xử lý đăng nhập
    public function checkLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            $account = $this->accountModel->getAccountByUsername($username);

            if ($account && password_verify($password, $account->password)) {
                SessionHelper::start();
                $_SESSION['username'] = $account->username;
                $_SESSION['role'] = $account->role;
                header('Location: /webbanhang/product');
                exit;
            } else {
                $error = $account ? "Mật khẩu không đúng!" : "Không tìm thấy tài khoản!";
                include_once 'app/views/account/login.php';
                exit;
            }
        }
    }
}
?>
