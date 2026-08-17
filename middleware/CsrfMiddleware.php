<?php

namespace Middleware;

class CsrfMiddleware
{
    public static function generateToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION["csrf_token"])) {
            $_SESSION["csrf_token"] = bin2hex(
                random_bytes(32)
            );
        }

        return $_SESSION["csrf_token"];
    }

    public static function verify(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = $_POST["csrf_token"] ?? "";

        if (
            empty($token) ||
            empty($_SESSION["csrf_token"]) ||
            !hash_equals(
                $_SESSION["csrf_token"],
                $token
            )
        ) {
            die("CSRF Token không hợp lệ.");
        }
    }
}