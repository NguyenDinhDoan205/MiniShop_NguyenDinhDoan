<?php
require_once "../../../dao/ProductDAO.php";

$pageTitle = "Danh sách Sản phẩm";

$productDAO = new ProductDAO();

$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
$page = $page <= 0 ? 1 : $page;

$pageSize = 5;

$keyword = trim($_GET["keyword"] ?? "");

if ($keyword != "") {
    $products = $productDAO->search($keyword);
    $total = count($products);
} else {
    $products = $productDAO->paging($page, $pageSize);
    $total = $productDAO->count();
}

$totalPage = ceil($total / $pageSize);

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-3">

    <h2>Danh sách sản phẩm</h2>

    <a href="create.php" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i>
        Thêm mới
    </a>

</div>

        <div class="card-body">

            <form method="get" class="row g-2 mb-3">

                <div class="col-md-5">

                    <input
                        type="text"
                        name="keyword"
                        class="form-control"
                        placeholder="Nhập tên sản phẩm..."
                        value="<?= htmlspecialchars($keyword) ?>">

                </div>

                <div class="col-auto">

                    <button class="btn btn-primary">

                        <i class="bi bi-search"></i>
                        Tìm kiếm

                    </button>

                    <a href="index.php" class="btn btn-secondary">

                        <i class="bi bi-arrow-clockwise"></i>
                        Làm mới

                    </a>

                </div>

            </form>

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark text-center">

                        <tr>

                            <th width="60">STT</th>

                            <th width="90">Ảnh</th>

                            <th>Tên sản phẩm</th>

                            <th>Slug</th>

                            <th>Giá</th>

                            <th>Giảm giá</th>

                            <th>SL</th>

                            <th>Trạng thái</th>

                            <th width="240">Thao tác</th>

                        </tr>

                    </thead>

                   <tbody>

<?php if (count($products) == 0): ?>

    <tr>
        <td colspan="9" class="text-center text-danger">
            Không có dữ liệu.
        </td>
    </tr>

<?php else: ?>

    <?php
    $stt = ($page - 1) * $pageSize + 1;

    foreach ($products as $item):
    ?>

    <tr>

        <td class="text-center">
            <?= $stt++ ?>
        </td>

        <td class="text-center">

           <?php if (!empty($item->image)): ?>

                    <img
                        src="../../../uploads/<?= $item->image ?>"
                        width="80"
                        class="img-thumbnail">

                <?php else: ?>

                    Không có ảnh

                <?php endif; ?>
        </td>

        <td>
            <strong><?= htmlspecialchars($item->proname) ?></strong>
        </td>

        <td>
            <?= htmlspecialchars($item->slug) ?>
        </td>

        <td class="text-end text-primary fw-bold">
            <?= number_format($item->price, 0, ",", ".") ?> đ
        </td>

        <td class="text-end text-danger fw-bold">
            <?= number_format($item->discountPrice, 0, ",", ".") ?> đ
        </td>

        <td class="text-center">
            <?= $item->quantity ?>
        </td>

        <td class="text-center">

            <?php if ($item->status == 1): ?>

                <span class="badge bg-success">
                    Hiển thị
                </span>

            <?php else: ?>

                <span class="badge bg-danger">
                    Ẩn
                </span>

            <?php endif; ?>

        </td>

        <td class="text-center">

            <a href="detail.php?id=<?= $item->id ?>"
                class="btn btn-info btn-sm">
                <i class="bi bi-eye"></i> Chi tiết
            </a>

            <a href="edit.php?id=<?= $item->id ?>"
                class="btn btn-warning btn-sm">
                <i class="bi bi-pencil-square"></i> Sửa
            </a>

            <a href="delete.php?id=<?= $item->id ?>"
                class="btn btn-danger btn-sm"
                onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                <i class="bi bi-trash"></i> Xóa
            </a>

        </td>

    </tr>

    <?php endforeach; ?>

<?php endif; ?>

</tbody>

</table>

</div>  

<?php if ($keyword == ""): ?>

<nav class="mt-3">

    <ul class="pagination justify-content-center">

        <?php for ($i = 1; $i <= $totalPage; $i++): ?>

            <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">

                <a class="page-link"
                    href="?page=<?= $i ?>">

                    <?= $i ?>

                </a>

            </li>

        <?php endfor; ?>

    </ul>

</nav>

<?php endif; ?>

</div>

</div>

</div>

<?php

$content = ob_get_clean();

include "../layouts/master.php";

?>