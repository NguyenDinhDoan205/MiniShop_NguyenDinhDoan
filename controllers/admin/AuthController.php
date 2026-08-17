<?php

namespace Controllers\Admin;

use DAO\UserDAO;
use Models\User;
use Middleware\CsrfMiddleware;

class AuthController
{
    protected UserDAO $userDAO;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->userDAO = new UserDAO();
    }

    /**
     * Xử lý đăng nhập — TRẢ VỀ array, KHÔNG require view
     */
    public function login(): array
    {
        $username = "";
        $password = "";
        $error = "";
        $errors = [
            "username" => "",
            "password" => ""
        ];

        if (!isset($_SESSION["csrf_token"])) {
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            CsrfMiddleware::verify();

            $username = trim($_POST["username"] ?? "");
            $password = trim($_POST["password"] ?? "");
            $remember = isset($_POST["remember"]);

            if ($username === "" || $password === "") {

                $error = "Vui lòng nhập đầy đủ thông tin.";
            } else {

                $user = $this->userDAO->findByUsername($username);

                if ($user === null) {

                    $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
                } elseif ($password !== $user->password) {

                    $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
                } elseif ((int)$user->status !== 1) {

                    $error = "Tài khoản đã bị khóa.";
                } elseif ((int)$user->role !== 1) {

                    $error = "Tài khoản của bạn không có quyền truy cập trang quản trị.";
                } else {

                    $_SESSION["user"] = $user;

                    if ($remember) {
                        $this->handleRememberToken($user->id);
                    }

                    header("Location: /MiniShop_NguyenDinhDoan/index.php?area=admin&controller=product&action=index");
                    exit;
                }
            }
        }

        // Trả dữ liệu về cho index.php để extract() ra view
        return [
            "username" => $username,
            "error" => $error,
            "errors" => $errors,
        ];
    }

    /**
     * Xử lý đăng xuất — tự redirect, không cần view
     */
    public function logout(): void
    {
        if (isset($_SESSION["user"])) {

            $user = $_SESSION["user"];

            if ($user instanceof User) {
                $this->userDAO->clearRememberToken($user->id);
            }
        }

        setcookie(
            "remember_token",
            "",
            time() - 3600,
            "/"
        );

        $_SESSION = [];
        session_destroy();

        header("Location: /MiniShop_NguyenDinhDoan/index.php?area=admin&controller=auth&action=login");
        exit;
    }

    private function handleRememberToken(int $userId): void
    {
        $token = bin2hex(random_bytes(32));
        $expiryTimestamp = time() + (30 * 24 * 60 * 60);
        $expiry = date("Y-m-d H:i:s", $expiryTimestamp);

        $saved = $this->userDAO->saveRememberToken($userId, $token, $expiry);

        if ($saved) {
            setcookie(
                "remember_token",
                $token,
                [
                    "expires" => $expiryTimestamp,
                    "path" => "/",
                    "secure" => false,
                    "httponly" => true,
                    "samesite" => "Lax"
                ]
            );
        }
    }
}