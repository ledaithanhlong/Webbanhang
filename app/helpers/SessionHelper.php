<?php
class SessionHelper
{
    // Bắt đầu session nếu chưa khởi động
    public static function start()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Kiểm tra người dùng đã đăng nhập chưa
    public static function isLoggedIn()
    {
        self::start();
        return isset($_SESSION['username']);
    }

    // Kiểm tra người dùng là user hoặc admin (người dùng hệ thống)
    public static function isUser()
    {
        self::start();
        return isset($_SESSION['username']) && isset($_SESSION['role']) &&
               in_array($_SESSION['role'], ['user', 'admin']);
    }

    // Kiểm tra người dùng có phải admin không
    public static function isAdmin()
    {
        self::start();
        return isset($_SESSION['username']) && isset($_SESSION['role']) &&
               $_SESSION['role'] === 'admin';
    }

    // Lấy tên người dùng đang đăng nhập (nếu có)
    public static function getUsername()
    {
        self::start();
        return $_SESSION['username'] ?? null;
    }

    // Lấy vai trò của người dùng, mặc định là 'guest'
    public static function getRole()
    {
        self::start();
        return $_SESSION['role'] ?? 'guest';
    }

    // Kiểm tra người dùng có đúng vai trò được truyền vào không
    public static function hasRole($role)
    {
        self::start();
        return isset($_SESSION['role']) && $_SESSION['role'] === $role;
    }

    // Đăng xuất người dùng
    public static function logout()
    {
        self::start();
        unset($_SESSION['username']);
        unset($_SESSION['role']);
        session_destroy();
    }
}
?>
