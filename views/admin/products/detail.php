<?php
if (!defined('APP_ENTRY')) {
    header("Location: /MiniShop_NguyenDinhDoan/admin/login");
    exit;
}

$pageTitle = $pageTitle ?? "Chi tiết sản phẩm";
$product = $product ?? null;
$gallery = $gallery ?? [];

if ($product === null) {
    die("Không tìm thấy sản phẩm.");
}

ob_start();
?>

<h2 class="mb-4">Chi Tiết Sản Phẩm</h2>

<div class="card">

    <div class="card-header bg-primary text-white">
        Thông tin sản phẩm
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <tr>
                <th width="220">ID</th>
                <td><?= $product->id ?></td>
            </tr>

            <tr>
                <th>Tên sản phẩm</th>
                <td>
                    <?= htmlspecialchars($product->proname) ?>
                </td>
            </tr>

            <tr>
                <th>Slug</th>
                <td>
                    <?= htmlspecialchars($product->slug) ?>
                </td>
            </tr>

            <tr>
                <th>Danh mục</th>
                <td>
                    <?= htmlspecialchars($product->catename) ?>
                </td>
            </tr>

            <tr>
                <th>Thương hiệu</th>
                <td>
                    <?= htmlspecialchars($product->brandname) ?>
                </td>
            </tr>

            <tr>
                <th>Giá</th>
                <td class="text-primary fw-bold">
                    <?= number_format($product->price, 0, ",", ".") ?> đ
                </td>
            </tr>

            <tr>
                <th>Giá khuyến mãi</th>
                <td class="text-danger fw-bold">
                    <?= number_format($product->discountPrice, 0, ",", ".") ?> đ
                </td>
            </tr>

            <tr>
                <th>Số lượng</th>
                <td>
                    <?= $product->quantity ?>
                </td>
            </tr>

            <tr>
                <th>Hình ảnh chính</th>

                <td>

                    <?php if (!empty($product->image)): ?>

                        <img
                            src="/MiniShop_NguyenDinhDoan/uploads/<?= htmlspecialchars($product->image) ?>"
                            width="150"
                            class="img-thumbnail">

                    <?php else: ?>

                        <span class="text-muted">
                            Không có ảnh
                        </span>

                    <?php endif; ?>

                </td>
            </tr>

            <tr>
                <th>Hình ảnh phụ</th>

                <td>

                    <?php if (!empty($gallery)): ?>

                        <div class="d-flex flex-wrap gap-2">

                            <?php foreach ($gallery as $item): ?>

                                <img
                                    src="/MiniShop_NguyenDinhDoan/uploads/<?= htmlspecialchars($item["image"]) ?>"
                                    width="120"
                                    height="120"
                                    class="img-thumbnail"
                                    style="object-fit: cover;">

                            <?php endforeach; ?>

                        </div>

                    <?php else: ?>

                        <span class="text-muted">
                            Không có hình ảnh phụ
                        </span>

                    <?php endif; ?>

                </td>
            </tr>

            <tr>
                <th>Mô tả</th>

                <td>
                    <?= nl2br(htmlspecialchars($product->description)) ?>
                </td>
            </tr>

            <tr>
                <th>Trạng thái</th>

                <td>

                    <?php if ($product->status == 1): ?>

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

                <td>
                    <?= $product->createdAt ?>
                </td>
            </tr>

            <tr>
                <th>Ngày cập nhật</th>

                <td>
                    <?= $product->updatedAt ?>
                </td>
            </tr>

        </table>

        
           <a href="/MiniShop_NguyenDinhDoan/admin/product/edit/<?= $product->id ?>"
            class="btn btn-warning">

            <i class="bi bi-pencil-square"></i>
            Cập nhật

        </a>

        
            <a href="/MiniShop_NguyenDinhDoan/admin/product"
            class="btn btn-secondary">

            <i class="bi bi-arrow-left"></i>
            Quay lại

        </a>

    </div>

</div>

<?php

$content = ob_get_clean();

require __DIR__ . "/../layouts/master.php";