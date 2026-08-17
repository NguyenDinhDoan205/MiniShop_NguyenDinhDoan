<?php

namespace Middleware;

class RoleMiddleware
{
    public static function requireRole(int $requiredRole): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION["user"])) {
            header("Location: /MiniShop_NguyenDinhDoan/index.php?controller=auth&action=login");
            exit;
        }

        $user = $_SESSION["user"];

        $role = 0;

        if (is_object($user)) {
            $role = (int)($user->role ?? 0);
        } elseif (is_array($user)) {
            $role = (int)($user["role"] ?? 0);
        }

        if ($role !== $requiredRole) {
            http_response_code(403);
            die("Bạn không có quyền truy cập!");
        }
    }
}