<?php
require_once "../../../dao/CategoryDAO.php";
require_once "../../../models/Category.php";

$pageTitle = "Thêm danh mục";

$categoryDAO = new CategoryDAO();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $catename = trim($_POST["catename"]);
    $slug = trim($_POST["slug"]);
    $status = $_POST["status"];

    if ($catename == "") {
        $error = "Tên danh mục không được để trống.";
    } else {

        $category = new Category();

        $category->catename = $catename;
        $category->slug = $slug;
        $category->status = $status;

        $categoryDAO->insert($category);

        header("Location: index.php");
        exit;
    }
}

ob_start();
?>

<h2 class="mb-4">Thêm danh mục</h2>

<?php if ($error != ""): ?>
<div class="alert alert-danger">
    <?= $error ?>
</div>
<?php endif; ?>

<form method="post">

    <div class="mb-3">
        <label class="form-label">Tên danh mục</label>

        <input
            type="text"
            name="catename"
            class="form-control"
            value="<?= $_POST["catename"] ?? "" ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Slug</label>

        <input
            type="text"
            name="slug"
            class="form-control"
            value="<?= $_POST["slug"] ?? "" ?>">
    </div>

    <div class="mb-3">

        <label class="form-label">
            Trạng thái
        </label>

        <select
            name="status"
            class="form-select">

            <option value="1">Hiển thị</option>

            <option value="0">Ẩn</option>

        </select>

    </div>

    <button
        type="submit"
        class="btn btn-success">

        <i class="bi bi-save"></i>
        Lưu

    </button>

    <a
        href="index.php"
        class="btn btn-secondary">

        Quay lại

    </a>

</form>

<?php

$content = ob_get_clean();

include "../layouts/master.php";