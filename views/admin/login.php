<?php

require_once "../../models/User.php";
require_once "../../dao/UserDAO.php";
require_once "../../middleware/GuestMiddleware.php";
require_once "../../middleware/CsrfMiddleware.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

GuestMiddleware::handle();

$userDAO = new UserDAO();

$username = "";
$password = "";
$error = "";
$csrfToken = CsrfMiddleware::generateToken();
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

        $user = $userDAO->findByUsername($username);

        if ($user === null) {

            $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
        } elseif ($password !== $user->password) {

            $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
        } elseif ((int)$user->status !== 1) {

            $error = "Tài khoản đã bị khóa.";
        } elseif ((int)$user->role !== 1) {

            $error =
                "Tài khoản của bạn không có quyền truy cập trang quản trị.";
        } else {
            $_SESSION["user"] = $user;

            if ($remember) {

                $token = bin2hex(random_bytes(32));
                $expiryTimestamp = time() + (30 * 24 * 60 * 60);
                $expiry = date(
                    "Y-m-d H:i:s",
                    $expiryTimestamp
                );
                $saved = $userDAO->saveRememberToken(
                    $user->id,
                    $token,
                    $expiry
                );
                if (!$saved) {
                    $error = "Không thể lưu Remember Token.";
                } else {
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
    }
}
?>





<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Đăng nhập quản trị</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container">

        <div class="row justify-content-center mt-5">

            <div class="col-md-5">

                <div class="card shadow border-0">

                    <div class="card-body p-4">

                        <h3 class="text-center mb-4">
                            Đăng nhập quản trị
                        </h3>
                        <?php if ($error !== ""): ?>

                            <div class="alert alert-danger">
                                <?= htmlspecialchars($error) ?>
                            </div>

                        <?php endif; ?>


                        <form
                            action="login.php"
                            method="POST">
                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
                            <div class="mb-3">

                                <label class="form-label">
                                    Tên đăng nhập
                                </label>

                                <input
                                    type="text"
                                    name="username"
                                    value="<?= htmlspecialchars($username) ?>"
                                    class="form-control"
                                    placeholder="Nhập tên đăng nhập"
                                    autocomplete="username">

                                <?php if ($errors["username"] !== ""): ?>

                                    <div class="text-danger mt-1">
                                        <?= htmlspecialchars($errors["username"]) ?>
                                    </div>

                                <?php endif; ?>

                            </div>
                            <div class="mb-3">

                                <label class="form-label">
                                    Mật khẩu
                                </label>

                                <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    placeholder="Nhập mật khẩu"
                                    autocomplete="current-password">

                                <?php if ($errors["password"] !== ""): ?>

                                    <div class="text-danger mt-1">
                                        <?= htmlspecialchars($errors["password"]) ?>
                                    </div>

                                <?php endif; ?>

                            </div>
                            <div class="mb-3 form-check">

                                <input
                                    type="checkbox"
                                    name="remember"
                                    class="form-check-input"
                                    id="remember">

                                <label
                                    class="form-check-label"
                                    for="remember">
                                    Ghi nhớ đăng nhập
                                </label>

                            </div>
                            <div class="d-grid">

                                <button
                                    type="submit"
                                    class="btn btn-primary">
                                    Đăng nhập
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>