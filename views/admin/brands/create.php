<?php
require_once "../../../models/Brand.php";
require_once "../../../dao/BrandDAO.php";
require_once "../../../middleware/CsrfMiddleware.php";

$pageTitle = "Thêm thương hiệu";

$brandDAO = new BrandDAO();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    CsrfMiddleware::verify();

    $brandname = trim($_POST["brandname"]);
    $slug = trim($_POST["slug"]);
    $description = trim($_POST["description"]);
    $status = (int)$_POST["status"];

    $image = null;


    if ($brandname == "") {
        $errors["brandname"] = "Tên thương hiệu không được để trống";
    }

    if ($slug == "") {
        $errors["slug"] = "Slug không được để trống";
    }


    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {

        $image = time() . "_" . $_FILES["image"]["name"];

        move_uploaded_file(
            $_FILES["image"]["tmp_name"],
            "../../../uploads/brands/" . $image
        );
    }

    if (empty($errors)) {

        $brand = new Brand(
            $brandname,
            $slug,
            $image,
            $description,
            $status
        );

        $brandDAO->insert($brand);

        header("Location: index.php");
        exit;
    }
}

ob_start();
?>

<h2 class="mb-4">Thêm thương hiệu</h2>

<form action="create.php" method="POST">

    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>"
    >


    <div class="mb-3">

        <label class="form-label">Tên thương hiệu</label>

        <input
            type="text"
            name="brandname"
            class="form-control"
            value="<?= $_POST["brandname"] ?? "" ?>">

        <small class="text-danger">
            <?= $errors["brandname"] ?? "" ?>
        </small>

    </div>

    <div class="mb-3">

        <label class="form-label">Slug</label>

        <input
            type="text"
            name="slug"
            class="form-control"
            value="<?= $_POST["slug"] ?? "" ?>">

        <small class="text-danger">
            <?= $errors["slug"] ?? "" ?>
        </small>

    </div>

    <div class="mb-3">

        <label class="form-label">
            Hình ảnh
        </label>

        <input
            type="file"
            name="image"
            class="form-control">

    </div>

    <div class="mb-3">

        <label class="form-label">
            Mô tả
        </label>

        <textarea
            name="description"
            rows="4"
            class="form-control"><?= $_POST["description"] ?? "" ?></textarea>

    </div>

    <div class="mb-3">

        <label class="form-label">
            Trạng thái
        </label>

        <div class="form-check">

            <input
                class="form-check-input"
                type="radio"
                name="status"
                value="1"
                checked>

            <label class="form-check-label">
                Hoạt động
            </label>

        </div>

        <div class="form-check">

            <input
                class="form-check-input"
                type="radio"
                name="status"
                value="0">

            <label class="form-check-label">
                Ngừng hoạt động
            </label>

        </div>

    </div>

    <button class="btn btn-primary">
        <i class="bi bi-save"></i>
        Lưu
    </button>

    <a href="index.php" class="btn btn-secondary">
        Quay lại
    </a>

</form>

<?php

$content = ob_get_clean();

include "../layouts/master.php";