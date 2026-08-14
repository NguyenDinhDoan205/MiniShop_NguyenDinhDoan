<?php

require_once "../../../models/User.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once "../../../middleware/RoleMiddleware.php";

RoleMiddleware::requireRole(1);


/*
|--------------------------------------------------------------------------
| Load DAO
|--------------------------------------------------------------------------
*/

require_once "../../../dao/UserDAO.php";


/*
|--------------------------------------------------------------------------
| Khởi tạo
|--------------------------------------------------------------------------
*/

$pageTitle = "Cập nhật người dùng";

$userDAO = new UserDAO();


$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;


if ($id <= 0) {

    header("Location: index.php");

    exit;
}

$user = $userDAO->findById($id);


if ($user === null) {

    header("Location: index.php");

    exit;
}


$error = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullname = trim($_POST["fullname"] ?? "");

    $username = trim($_POST["username"] ?? "");

    $password = trim($_POST["password"] ?? "");

    $email = trim($_POST["email"] ?? "");

    $phone = trim($_POST["phone"] ?? "");

    $address = trim($_POST["address"] ?? "");

    $role = (int)($_POST["role"] ?? 0);

    $status = (int)($_POST["status"] ?? 1);

    if ($fullname === "") {

        $error = "Vui lòng nhập họ tên.";

    } elseif ($username === "") {

        $error = "Vui lòng nhập username.";

    } elseif ($email === "") {

        $error = "Vui lòng nhập email.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Email không hợp lệ.";

    } elseif ($role !== 0 && $role !== 1) {

        $error = "Vai trò không hợp lệ.";

    } elseif ($status !== 0 && $status !== 1) {

        $error = "Trạng thái không hợp lệ.";

    } else {

        if ($password === "") {

            $password = $user->password;
        }

        $user->fullname = $fullname;

        $user->username = $username;

        $user->password = $password;

        $user->email = $email;

        $user->phone = $phone;

        $user->address = $address;

        $user->role = $role;

        $user->status = $status;

        if ($userDAO->update($user)) {

            header("Location: index.php");

            exit;

        } else {

            $error = "Cập nhật người dùng thất bại.";
        }
    }
}

ob_start();

?>


<div class="container mt-4">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            <h4 class="mb-0">

                <i class="bi bi-person-gear"></i>

                Cập nhật người dùng

            </h4>

        </div>


        <div class="card-body">

            <?php if ($error !== ""): ?>

                <div class="alert alert-danger">

                    <?= htmlspecialchars($error) ?>

                </div>

            <?php endif; ?>
            <form method="POST">

                <div class="mb-3">

                    <label class="form-label">

                        Họ tên

                    </label>


                    <input
                        type="text"
                        name="fullname"
                        class="form-control"
                        value="<?= htmlspecialchars($user->fullname) ?>"
                        placeholder="Nhập họ tên"
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Username

                    </label>


                    <input
                        type="text"
                        name="username"
                        class="form-control"
                        value="<?= htmlspecialchars($user->username) ?>"
                        placeholder="Nhập username"
                    >

                </div>


                <!-- PASSWORD -->

                <div class="mb-3">

                    <label class="form-label">

                        Mật khẩu

                    </label>


                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Để trống nếu không muốn đổi mật khẩu"
                    >


                    <small class="text-muted">

                        Để trống nếu muốn giữ mật khẩu cũ.

                    </small>

                </div>


                <!-- EMAIL -->

                <div class="mb-3">

                    <label class="form-label">

                        Email

                    </label>


                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="<?= htmlspecialchars($user->email) ?>"
                        placeholder="example@gmail.com"
                    >

                </div>


                <!-- SỐ ĐIỆN THOẠI -->

                <div class="mb-3">

                    <label class="form-label">

                        Số điện thoại

                    </label>


                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        value="<?= htmlspecialchars($user->phone) ?>"
                        placeholder="Nhập số điện thoại"
                    >

                </div>


                <!-- ĐỊA CHỈ -->

                <div class="mb-3">

                    <label class="form-label">

                        Địa chỉ

                    </label>


                    <textarea
                        name="address"
                        class="form-control"
                        rows="3"
                        placeholder="Nhập địa chỉ"
                    ><?= htmlspecialchars($user->address) ?></textarea>

                </div>


                <!--
                |--------------------------------------------------------------------------
                | VAI TRÒ
                |--------------------------------------------------------------------------
                -->

                <div class="mb-3">

                    <label class="form-label">

                        Vai trò

                    </label>


                    <select
                        name="role"
                        class="form-select"
                    >

                        <option
                            value="0"
                            <?= ((int)$user->role === 0)
                                ? "selected"
                                : "" ?>
                        >

                            User

                        </option>


                        <option
                            value="1"
                            <?= ((int)$user->role === 1)
                                ? "selected"
                                : "" ?>
                        >

                            Admin

                        </option>

                    </select>

                </div>


                <!--
                |--------------------------------------------------------------------------
                | TRẠNG THÁI
                |--------------------------------------------------------------------------
                -->

                <div class="mb-3">

                    <label class="form-label">

                        Trạng thái

                    </label>


                    <select
                        name="status"
                        class="form-select"
                    >

                        <option
                            value="1"
                            <?= ((int)$user->status === 1)
                                ? "selected"
                                : "" ?>
                        >

                            Hoạt động

                        </option>


                        <option
                            value="0"
                            <?= ((int)$user->status === 0)
                                ? "selected"
                                : "" ?>
                        >

                            Khóa

                        </option>

                    </select>

                </div>


                <!--
                |--------------------------------------------------------------------------
                | BUTTON
                |--------------------------------------------------------------------------
                -->

                <div class="mt-4">


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-save"></i>

                        Cập nhật

                    </button>


                    <a
                        href="index.php"
                        class="btn btn-secondary"
                    >

                        <i class="bi bi-arrow-left"></i>

                        Quay lại

                    </a>

                </div>


            </form>


        </div>

    </div>

</div>


<?php

$content = ob_get_clean();

include "../layouts/master.php";

?>