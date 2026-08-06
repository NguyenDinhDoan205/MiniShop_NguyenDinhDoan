<?php
require_once "../../../dao/BrandDAO.php";

$pageTitle = "Chi tiết thương hiệu";

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

ob_start();
?>

<h2 class="mb-4">Chi tiết thương hiệu</h2>

<div class="card">

    <div class="card-header bg-primary text-white">
        Thông tin thương hiệu
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <tr>
                <th width="220">ID</th>
                <td><?= $brand->id ?></td>
            </tr>

            <tr>
                <th>Tên thương hiệu</th>
                <td><?= $brand->brandname ?></td>
            </tr>

            <tr>
                <th>Slug</th>
                <td><?= $brand->slug ?></td>
            </tr>

            <tr>
                <th>Hình ảnh</th>

                <td>

                    <?php if($brand->image != ""): ?>

                        <img
                            src="../../../uploads/brands/<?= $brand->image ?>"
                            width="150"
                            class="img-thumbnail">

                    <?php else: ?>

                        Không có hình

                    <?php endif; ?>

                </td>

            </tr>

            <tr>
                <th>Mô tả</th>
                <td><?= nl2br($brand->description) ?></td>
            </tr>

            <tr>
                <th>Trạng thái</th>

                <td>

                    <?php if($brand->status == 1): ?>

                        <span class="badge bg-success">
                            Hoạt động
                        </span>

                    <?php else: ?>

                        <span class="badge bg-danger">
                            Ngừng hoạt động
                        </span>

                    <?php endif; ?>

                </td>

            </tr>

            <tr>
                <th>Ngày tạo</th>
                <td><?= $brand->createdAt ?></td>
            </tr>

            <tr>
                <th>Ngày cập nhật</th>
                <td><?= $brand->updatedAt ?></td>
            </tr>

        </table>

        <a href="edit.php?id=<?= $brand->id ?>" class="btn btn-warning">
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