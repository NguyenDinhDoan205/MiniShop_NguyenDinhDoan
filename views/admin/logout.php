<?php

require_once "../../models/User.php";
require_once "../../dao/UserDAO.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userDAO = new UserDAO();

if (isset($_SESSION["user"])) {

    $user = $_SESSION["user"];

    if ($user instanceof User) {
        $userDAO->clearRememberToken($user->id);
    }
}

/*
 * Xóa Cookie
 */
setcookie(
    "remember_token",
    "",
    time() - 3600,
    "/"
);

/*
 * Xóa Session
 */
$_SESSION = [];

session_destroy();

header(
    "Location: login.php"
);

exit;