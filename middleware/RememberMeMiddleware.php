<?php

require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/../dao/UserDAO.php";

class RememberMeMiddleware
{
    public static function handle()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        /*
         * Đã đăng nhập rồi thì không cần xử lý
         */
        if (isset($_SESSION["user"])) {
            return;
        }

        /*
         * Không có Cookie Remember Me
         */
        if (!isset($_COOKIE["remember_token"])) {
            return;
        }

        $token = $_COOKIE["remember_token"];

        /*
         * Token rỗng
         */
        if ($token === "") {
            return;
        }

        $userDAO = new UserDAO();

        /*
         * Tìm User bằng token
         */
        $user = $userDAO->findByRememberToken($token);

        /*
         * Token không hợp lệ
         */
        if ($user === null) {

            setcookie(
                "remember_token",
                "",
                time() - 3600,
                "/"
            );

            return;
        }

        /*
         * Chỉ Admin mới được khôi phục
         * Session Admin
         */
        if ((int)$user->role !== 1) {

            setcookie(
                "remember_token",
                "",
                time() - 3600,
                "/"
            );

            return;
        }

        /*
         * Tạo lại Session
         */
        $_SESSION["user"] = $user;
    }
}