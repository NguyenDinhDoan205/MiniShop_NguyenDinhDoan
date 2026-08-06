<?php
require_once "../../../dao/CategoryDAO.php";

$pageTitle = "Chi tiết danh mục";

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

ob_start();
?>

<h2 class="mb-4">Chi tiết danh mục</h2>

<div class="card shadow">

    <div class="card-header bg-primary text-white">
        Thông tin danh mục
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <tr>
                <th width="200">ID</th>
                <td><?= $category->id ?></td>
            </tr>

            <tr>
                <th>Tên danh mục</th>
                <td><?= $category->catename ?></td>
            </tr>

            <tr>
                <th>Slug</th>
                <td><?= $category->slug ?></td>
            </tr>

            <tr>
                <th>Trạng thái</th>
                <td>
                    <?php if ($category->status == 1): ?>

                        <span class="badge bg-success">
                            Hiển thị
                        </span>

                    <?php else: ?>

                        <span class="badge bg-secondary">
                            Ẩn
                        </span>

                    <?php endif; ?>
                </td>
            </tr>

        </table>

        <a href="edit.php?id=<?= $category->id ?>" class="btn btn-warning">
            <i class="bi bi-pencil"></i>
            Cập nhật
        </a>

        <a href="index.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>
            Quay lại
        </a>

    </div>

</div>

<?php

$content = ob_get_clean();

include "../layouts/master.php";