<?php

class CsrfMiddleware
{
    public static function generateToken()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION["csrf_token"])) {
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        }

        return $_SESSION["csrf_token"];
    }

    public static function verify()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $sessionToken = $_SESSION["csrf_token"] ?? "";
        $formToken = $_POST["csrf_token"] ?? "";

        if (
            empty($sessionToken) ||
            empty($formToken) ||
            !hash_equals($sessionToken, $formToken)
        ) {
            die("CSRF Token không hợp lệ.");
        }

        return true;
    }
}