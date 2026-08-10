<?php
require_once "../../../dao/UserDAO.php";

$pageTitle = "Chi tiết người dùng";
$userDAO = new UserDAO();

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($id <= 0) {

    header("Location: index.php");

    exit;
}

$user = $userDAO->findById($id);

if ($user == null) {

    header("Location: index.php");

    exit;
}
ob_start();

?>


<div class="d-flex justify-content-between align-items-center mb-4">

    <h2>
        Chi tiết người dùng
    </h2>

    <a
        href="index.php"
        class="btn btn-secondary">

        <i class="bi bi-arrow-left"></i>

        Quay lại

    </a>

</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <i class="bi bi-person"></i>
        Thông tin người dùng

    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">

                <label class="fw-bold">

                    ID

                </label>

                <div class="form-control bg-light">

                    <?= $user->id ?>

                </div>

            </div>


            <div class="col-md-6 mb-3">

                <label class="fw-bold">

                    Họ tên

                </label>

                <div class="form-control bg-light">

                    <?= htmlspecialchars($user->fullname) ?>

                </div>

            </div>

            <div class="col-md-6 mb-3">

                <label class="fw-bold">

                    Username

                </label>

                <div class="form-control bg-light">

                    <?= htmlspecialchars($user->username) ?>

                </div>

            </div>

            <div class="col-md-6 mb-3">

                <label class="fw-bold">

                    Email

                </label>

                <div class="form-control bg-light">

                    <?= htmlspecialchars($user->email) ?>

                </div>

            </div>

            <div class="col-md-6 mb-3">

                <label class="fw-bold">

                    Số điện thoại

                </label>

                <div class="form-control bg-light">

                    <?= htmlspecialchars($user->phone) ?>

                </div>

            </div>
            <div class="col-md-6 mb-3">

                <label class="fw-bold">

                    Vai trò

                </label>

                <div class="form-control bg-light">

                    <?php if ($user->role == 1): ?>

                        <span class="badge bg-primary">

                            Admin

                        </span>

                    <?php else: ?>

                        <span class="badge bg-secondary">

                            User

                        </span>

                    <?php endif; ?>

                </div>

            </div>
            <div class="col-md-6 mb-3">

                <label class="fw-bold">

                    Trạng thái

                </label>

                <div class="form-control bg-light">

                    <?php if ($user->status == 1): ?>

                        <span class="badge bg-success">

                            Hoạt động

                        </span>

                    <?php else: ?>

                        <span class="badge bg-danger">

                            Khóa

                        </span>

                    <?php endif; ?>

                </div>

            </div>

            <div class="col-md-6 mb-3">

                <label class="fw-bold">

                    Ngày tạo

                </label>

                <div class="form-control bg-light">

                    <?= htmlspecialchars($user->createdAt) ?>

                </div>

            </div>

            <div class="col-md-6 mb-3">

                <label class="fw-bold">

                    Ngày cập nhật

                </label>

                <div class="form-control bg-light">

                    <?= htmlspecialchars($user->updatedAt) ?>

                </div>

            </div>

            <div class="col-md-12 mb-3">

                <label class="fw-bold">

                    Địa chỉ

                </label>

                <div class="form-control bg-light">

                    <?= htmlspecialchars($user->address) ?>

                </div>

            </div>


        </div>

    </div>


    <div class="card-footer">

        <a
            href="edit.php?id=<?= $user->id ?>"
            class="btn btn-warning">

            <i class="bi bi-pencil"></i>

            Chỉnh sửa

        </a>


        <a
            href="index.php"
            class="btn btn-secondary">

            <i class="bi bi-arrow-left"></i>

            Quay lại

        </a>

    </div>

</div>


<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
