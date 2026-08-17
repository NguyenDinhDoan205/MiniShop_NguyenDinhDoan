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
        RememberMeMiddleware::handle();
        if (!isset($_SESSION["user"])) {

            header(
                "Location: /MiniShop_NguyenDinhDoan/views/admin/login.php"
            );

            exit;
        }

        $user = $_SESSION["user"];

        if (!($user instanceof User)) {

            unset($_SESSION["user"]);

            header(
                "Location: /MiniShop_NguyenDinhDoan/views/admin/login.php"
            );

            exit;
        }

        if ((int)$user->role !== $requiredRole) {

            header(
                "Location: /MiniShop_NguyenDinhDoan/views/admin/403.php"
            );

            exit;
        }
    }
}