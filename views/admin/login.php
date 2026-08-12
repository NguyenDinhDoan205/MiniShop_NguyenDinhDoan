<?php

require_once __DIR__ . "/../../models/User.php";
session_start();

require_once __DIR__ . "/../../dao/UserDAO.php";

$errors = [];

$username = $_POST["username"] ?? "";
$password = $_POST["password"] ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Kiểm tra username
    if (empty($username)) {
        $errors["username"] = "Vui lòng nhập tên đăng nhập.";
    }

    // Kiểm tra password
    if (empty($password)) {
        $errors["password"] = "Vui lòng nhập mật khẩu.";
    }

    // Nếu không có lỗi thì tiến hành đăng nhập
    if (empty($errors)) {

        $userDAO = new UserDAO();

        $user = $userDAO->findByUsername($username);

        // Không tìm thấy tài khoản
        if (!$user) {

            $errors["username"] = "Tên đăng nhập không tồn tại.";

        // Sai mật khẩu
        } elseif (!password_verify($password, $user->password)) {

            $errors["password"] = "Mật khẩu không chính xác.";

        // Đăng nhập thành công
        } else {

            // Lưu User object vào session
            $_SESSION["user"] = $user;

            // Chuyển sang trang quản trị
            header("Location: dashboard.php");
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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

            <div class="card shadow">

                <div class="card-body p-4">

                    <h3 class="text-center mb-4">
                        Đăng nhập
                    </h3>

                    <form action="login.php" method="POST">

                      
                        <div class="mb-3">

                            <label class="form-label">
                                Tên đăng nhập
                            </label>

                            <input
                                type="text"
                                name="username"
                                value="<?= htmlspecialchars($username) ?>"
                                class="form-control"
                            >

                            <?php if (isset($errors["username"])): ?>

                                <div class="text-danger">
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
                                required
                            >

                            <?php if (isset($errors["password"])): ?>

                                <div class="text-danger">
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