<?php
require_once 'app/helpers/SessionHelper.php';

class AdminController
{
    public function dashboard()
    {
        if (!SessionHelper::isAdmin()) {
            header('Location: /webbanhang/account/login');
            exit;
        }

        include 'app/views/admin/dashboard.php';
    }
}
?>
