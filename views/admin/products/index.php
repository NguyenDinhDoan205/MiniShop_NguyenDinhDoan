<?php

require_once "../../../dao/ProductDAO.php";

$pageTitle = "Danh sách Sản phẩm";

$productDAO = new ProductDAO();

$limit = 10;

$page = (int)($_GET["page"] ?? 1);

if ($page < 1) {
    $page = 1;
}

$keyword = trim($_GET["keyword"] ?? "");

$offset = ($page - 1) * $limit;

if ($keyword != "") {

    $products = $productDAO->search($keyword);

    $totalRecords = count($products);

    $totalPages = (int)ceil($totalRecords / $limit);

    if ($totalPages > 0 && $page > $totalPages) {
        $page = $totalPages;
        $offset = ($page - 1) * $limit;
    }

    $products = array_slice($products, $offset, $limit);

} else {

    $totalRecords = $productDAO->count("products");

    $totalPages = (int)ceil($totalRecords / $limit);

    if ($totalPages > 0 && $page > $totalPages) {
        $page = $totalPages;
        $offset = ($page - 1) * $limit;
    }

    $products = $productDAO->getPage($limit, $offset);
}

ob_start();

?>

<div class="d-flex justify-content-between align-items-center mb-3">

    <h2>Danh sách sản phẩm</h2>

    <a href="create.php" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i>
        Thêm mới
    </a>

</div>

<div class="card">

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

                <button type="submit" class="btn btn-primary">

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
                        <th>Danh mục</th>
                        <th>Thương hiệu</th>
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

                        <td colspan="10" class="text-center text-danger">

                            Không có dữ liệu.

                        </td>

                    </tr>

                <?php else: ?>

                    <?php

                    $stt = ($page - 1) * $limit + 1;

                    foreach ($products as $item):

                    ?>

                    <tr>

                        <td class="text-center">
                            <?= $stt++ ?>
                        </td>

                        <td class="text-center">

                            <?php if (!empty($item->image)): ?>

                                <img
                                    src="../../../uploads/<?= htmlspecialchars($item->image) ?>"
                                    width="80"
                                    height="80"
                                    class="img-thumbnail"
                                    style="object-fit: cover;">

                            <?php else: ?>

                                Không có ảnh

                            <?php endif; ?>

                        </td>

                        <td>

                            <strong>
                                <?= htmlspecialchars($item->proname) ?>
                            </strong>

                        </td>

                        <td class="text-center">

                            <?= htmlspecialchars($item->catename ?? '') ?>

                        </td>

                        <td class="text-center">

                            <?= htmlspecialchars($item->brandname ?? '') ?>

                        </td>

                        <td class="text-end text-primary fw-bold">

                            <?= number_format(
                                $item->price,
                                0,
                                ",",
                                "."
                            ) ?> đ

                        </td>

                        <td class="text-end text-danger fw-bold">

                            <?= number_format(
                                $item->discountPrice,
                                0,
                                ",",
                                "."
                            ) ?> đ

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

                            <a
                                href="detail.php?id=<?= $item->id ?>"
                                class="btn btn-info btn-sm">

                                <i class="bi bi-eye"></i>
                                Chi tiết

                            </a>

                            <a
                                href="edit.php?id=<?= $item->id ?>"
                                class="btn btn-warning btn-sm">

                                <i class="bi bi-pencil-square"></i>
                                Sửa

                            </a>

                            <a
                                href="delete.php?id=<?= $item->id ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa?')">

                                <i class="bi bi-trash"></i>
                                Xóa

                            </a>

                        </td>

                    </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

        <?php if ($totalPages > 1): ?>

            <nav>

                <ul class="pagination justify-content-center">

                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">

                        <?php if ($page <= 1): ?>

                            <span class="page-link">
                                Trước
                            </span>

                        <?php else: ?>

                            <?php if ($keyword != ""): ?>

                                <a
                                    class="page-link"
                                    href="?page=<?= $page - 1 ?>&keyword=<?= urlencode($keyword) ?>">

                                    Trước

                                </a>

                            <?php else: ?>

                                <a
                                    class="page-link"
                                    href="?page=<?= $page - 1 ?>">

                                    Trước

                                </a>

                            <?php endif; ?>

                        <?php endif; ?>

                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>

                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">

                            <?php if ($keyword != ""): ?>

                                <a
                                    class="page-link"
                                    href="?page=<?= $i ?>&keyword=<?= urlencode($keyword) ?>">

                                    <?= $i ?>

                                </a>

                            <?php else: ?>

                                <a
                                    class="page-link"
                                    href="?page=<?= $i ?>">

                                    <?= $i ?>

                                </a>

                            <?php endif; ?>

                        </li>

                    <?php endfor; ?>

                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">

                        <?php if ($page >= $totalPages): ?>

                            <span class="page-link">
                                Sau
                            </span>

                        <?php else: ?>

                            <?php if ($keyword != ""): ?>

                                <a
                                    class="page-link"
                                    href="?page=<?= $page + 1 ?>&keyword=<?= urlencode($keyword) ?>">

                                    Sau

                                </a>

                            <?php else: ?>

                                <a
                                    class="page-link"
                                    href="?page=<?= $page + 1 ?>">

                                    Sau

                                </a>

                            <?php endif; ?>

                        <?php endif; ?>

                    </li>

                </ul>

            </nav>

        <?php endif; ?>

    </div>

</div>

<?php

$content = ob_get_clean();

include "../layouts/master.php";

?>
