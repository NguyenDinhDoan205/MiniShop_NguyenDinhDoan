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

        $sessionToken = $_SESSION["csrf_token"] ?? "";

        $requestToken = $_POST["csrf_token"] ?? "";

        if (
            empty($sessionToken) ||
            empty($requestToken) ||
            !hash_equals($sessionToken, $requestToken)
        ) {
            die("CSRF Token không hợp lệ.");
        }
    }
}