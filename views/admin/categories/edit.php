<?php
require_once "../../../dao/CategoryDAO.php";
require_once "../../../models/Category.php";

$pageTitle = "Cập nhật danh mục";

$categoryDAO = new CategoryDAO();

if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit;
}

$id = (int)$_GET["id"];

$category = $categoryDAO->findById($id);

if ($category == null) {
    die("Không tìm thấy danh mục.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $category->catename = trim($_POST["catename"]);
    $category->slug = trim($_POST["slug"]);
    $category->status = (int)$_POST["status"];

    $categoryDAO->update($category);

    header("Location: index.php");
    exit;
}

ob_start();
?>

<h2 class="mb-4">Cập nhật danh mục</h2>

<form method="post">

    <div class="mb-3">
        <label class="form-label">Tên danh mục</label>

        <input
            type="text"
            name="catename"
            class="form-control"
            value="<?= htmlspecialchars($category->catename) ?>"
            required>
    </div>

    <div class="mb-3">
        <label class="form-label">Slug</label>

        <input
            type="text"
            name="slug"
            class="form-control"
            value="<?= htmlspecialchars($category->slug) ?>"
            required>
    </div>

    <div class="mb-3">
        <label class="form-label">Trạng thái</label>

        <select name="status" class="form-select">

            <option value="1"
                <?= $category->status == 1 ? "selected" : "" ?>>
                Hiển thị
            </option>

            <option value="0"
                <?= $category->status == 0 ? "selected" : "" ?>>
                Ẩn
            </option>

        </select>
    </div>

    <button type="submit" class="btn btn-primary">
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