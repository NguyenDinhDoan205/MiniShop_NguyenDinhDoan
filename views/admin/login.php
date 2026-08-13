<?php

require_once "../../dao/UserDAO.php";
require_once "../../middleware/GuestMiddleware.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

GuestMiddleware::handle();

$userDAO = new UserDAO();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

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

        } else {

            /*
             * Lưu User vào session
             */
            $_SESSION["user"] = $user;

            /*
             * Đăng nhập thành công
             */
            header("Location: ../index.php");
            exit;
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
        content="width=device-width, initial-scale=1.0"
    >

    <title>Đăng nhập quản trị</title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

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

                    <form
                        action="login.php"
                        method="POST"
                    >

                        <input
                            type="hidden"
                            name="csrf_token"
                            value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>"
                        >
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
                            >


                            <?php if (isset($errors["username"])): ?>

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
                            >


                            <?php if (isset($errors["password"])): ?>

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
                                id="remember"
                            >

                            <label
                                class="form-check-label"
                                for="remember"
                            >
                                Ghi nhớ đăng nhập
                            </label>

                        </div>

                        <div class="d-grid">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
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
