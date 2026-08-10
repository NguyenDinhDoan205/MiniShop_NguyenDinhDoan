<?php
require_once "../../../dao/ProductDAO.php";

$pageTitle = "Chi tiết sản phẩm";

$productDAO = new ProductDAO();

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

$product = $productDAO->findById($id);

if ($product == null) {
    die("Không tìm thấy sản phẩm.");
}

ob_start();
?>
<h2 class="mb-4">Chi Tiết Thương Hiệu</h2>

<div class="card">

    <div class="card-header bg-primary text-white">
        Thông tin thương hiệu
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <tr>
                <th width="220">ID</th>
                <td><?= $product->id ?></td>
            </tr>

            <tr>
                <th>Tên sản phẩm</th>
                <td><?= htmlspecialchars($product->proname) ?></td>
            </tr>

            <tr>
                <th>Slug</th>
                <td><?= htmlspecialchars($product->slug) ?></td>
            </tr>

            <tr>
                <th>Danh mục</th>
                <td><?= $product->catename ?></td>
            </tr>

            <tr>
                <th>Thương hiệu</th>
                <td><?= $product->brandname ?></td>
            </tr>

            <tr>
                <th>Giá</th>
                <td class="text-primary fw-bold">
                    <?= number_format($product->price,0,",",".") ?> đ
                </td>
            </tr>

            <tr>
                <th>Giá khuyến mãi</th>
                <td class="text-danger fw-bold">
                    <?= number_format($product->discountPrice,0,",",".") ?> đ
                </td>
            </tr>

            <tr>
                <th>Số lượng</th>
                <td><?= $product->quantity ?></td>
            </tr>

            <tr>

                <th>Hình ảnh</th>

                <td>

                    <?php if($product->image != ""): ?>

                        <img
                            src="../../../uploads/<?= $product->image ?>"
                            width="150"
                            class="img-thumbnail">

                    <?php endif; ?>

                </td>


            </tr>

            <tr>
                <th>Mô tả</th>
                <td><?= nl2br(htmlspecialchars($product->description)) ?></td>
            </tr>

            <tr>

                <th>Trạng thái</th>

                <td>

                    <?php if($product->status==1): ?>

                        <span class="badge bg-success">
                            Hiển thị
                        </span>

                    <?php else: ?>

                        <span class="badge bg-danger">
                            Ẩn
                        </span>

                    <?php endif; ?>

                </td>

            </tr>

            <tr>
                <th>Ngày tạo</th>
                <td><?= $product->createdAt ?></td>
            </tr>

            <tr>
                <th>Ngày cập nhật</th>
                <td><?= $product->updatedAt ?></td>
            </tr>

        </table>

        <a href="edit.php?id=<?= $product->id ?>" class="btn btn-warning">
            <i class="bi bi-pencil-square"></i>
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