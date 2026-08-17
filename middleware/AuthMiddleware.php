<?php

namespace Middleware;

class AuthMiddleware
{
    public static function handle()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION["user"])) {
            header("Location: /MiniShop_NguyenDinhDoan/index.php?area=admin&controller=auth&action=login");
            exit;
        }
    }
}