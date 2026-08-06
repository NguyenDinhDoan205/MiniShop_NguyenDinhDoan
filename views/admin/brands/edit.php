<?php
require_once "../../../dao/BrandDAO.php";
require_once "../../../models/Brand.php";

$pageTitle = "Cập nhật thương hiệu";

$brandDAO = new BrandDAO();

if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit;
}

$id = (int)$_GET["id"];
$brand = $brandDAO->findById($id);

if ($brand == null) {
    die("Không tìm thấy thương hiệu.");
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $brand->brandname = trim($_POST["brandname"]);
    $brand->slug = trim($_POST["slug"]);
    $brand->description = trim($_POST["description"]);
    $brand->status = (int)$_POST["status"];

    if ($brand->brandname == "") {
        $errors["brandname"] = "Tên thương hiệu không được để trống";
    }

    if ($brand->slug == "") {
        $errors["slug"] = "Slug không được để trống";
    }

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {

        $image = time() . "_" . $_FILES["image"]["name"];

        move_uploaded_file(
            $_FILES["image"]["tmp_name"],
            "../../../uploads/brands/" . $image
        );

        $brand->image = $image;
    }

    if (empty($errors)) {

        $brandDAO->update($brand);

        header("Location: index.php");
        exit;
    }
}

ob_start();
?>

<h2 class="mb-4">Cập nhật thương hiệu</h2>

<form method="post" enctype="multipart/form-data">

    <div class="mb-3">

        <label class="form-label">Tên thương hiệu</label>

        <input
            type="text"
            name="brandname"
            class="form-control"
            value="<?= htmlspecialchars($brand->brandname) ?>">

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
            value="<?= htmlspecialchars($brand->slug) ?>">

        <small class="text-danger">
            <?= $errors["slug"] ?? "" ?>
        </small>

    </div>

    <div class="mb-3">

        <label class="form-label">Hình ảnh hiện tại</label>
        <br>

        <?php if ($brand->image != ""): ?>

            <img
                src="../../../uploads/brands/<?= $brand->image ?>"
                width="120"
                class="img-thumbnail mb-2">

        <?php else: ?>

            <p>Chưa có ảnh</p>

        <?php endif; ?>

        <input
            type="file"
            name="image"
            class="form-control">

    </div>

    <div class="mb-3">

        <label class="form-label">Mô tả</label>

        <textarea
            name="description"
            rows="4"
            class="form-control"><?= htmlspecialchars($brand->description) ?></textarea>

    </div>

    <div class="mb-3">

        <label class="form-label">Trạng thái</label>

        <div class="form-check">

            <input
                class="form-check-input"
                type="radio"
                name="status"
                value="1"
                <?= $brand->status == 1 ? "checked" : "" ?>>

            <label class="form-check-label">
                Hoạt động
            </label>

        </div>

        <div class="form-check">

            <input
                class="form-check-input"
                type="radio"
                name="status"
                value="0"
                <?= $brand->status == 0 ? "checked" : "" ?>>

            <label class="form-check-label">
                Ngừng hoạt động
            </label>

        </div>

    </div>

    <button class="btn btn-primary">
        <i class="bi bi-save"></i>
        Cập nhật
    </button>

    <a href="index.php" class="btn btn-secondary">
        Quay lại
    </a>

</form>

<?php

$content = ob_get_clean();

include "../layouts/master.php";