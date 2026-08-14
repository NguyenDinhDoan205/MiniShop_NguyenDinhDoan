<?php

require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/RememberMeMiddleware.php";

class RoleMiddleware
{
    public static function requireRole(int $requiredRole)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        /*
         * Nếu Session mất thì thử khôi phục
         * bằng Cookie Remember Me
         */
        RememberMeMiddleware::handle();

        /*
         * Không đăng nhập
         */
        if (!isset($_SESSION["user"])) {

            header(
                "Location: /MiniShop_NguyenDinhDoan/views/admin/login.php"
            );

            exit;
        }

        $user = $_SESSION["user"];

        /*
         * Kiểm tra User object
         */
        if (!($user instanceof User)) {

            unset($_SESSION["user"]);

            header(
                "Location: /MiniShop_NguyenDinhDoan/views/admin/login.php"
            );

            exit;
        }

        /*
         * Kiểm tra quyền
         */
        if ((int)$user->role !== $requiredRole) {

            header(
                "Location: /MiniShop_NguyenDinhDoan/views/admin/403.php"
            );

            exit;
        }
    }
}