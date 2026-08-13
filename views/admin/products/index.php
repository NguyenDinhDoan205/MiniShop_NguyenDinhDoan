<?php

require_once "../../../dao/ProductDAO.php";

$pageTitle = "Danh sách Sản phẩm";

$productDAO = new ProductDAO();

$limit = (int)($_GET["limit"] ?? 10);

if ($limit != 10 && $limit != 20 && $limit != 30) {
    $limit = 10;
}

$page = (int)($_GET["page"] ?? 1);

if ($page < 1) {
    $page = 1;
}

$keyword = trim($_GET["keyword"] ?? "");

$sort = $_GET["sort"] ?? "name_asc";

$allowedSort = [
    "name_asc",
    "name_desc",
    "price_asc",
    "price_desc",
    "quantity_asc",
    "quantity_desc"
];

if (!in_array($sort, $allowedSort)) {
    $sort = "name_asc";
}

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

<form method="GET" class="mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label fw-semibold">
                Tìm kiếm sản phẩm
            </label>
            <input
                type="text"
                name="keyword"
                value="<?= htmlspecialchars($keyword) ?>"
                class="form-control"
                placeholder="Nhập tên sản phẩm...">
        </div>

        <div class="col-md-2">
            <label class="form-label fw-semibold">
                Sản phẩm / trang
            </label>

            <select name="limit" class="form-select">

                <option value="10" <?= $limit == 10 ? "selected" : "" ?>>
                    10 sản phẩm
                </option>

                <option value="20" <?= $limit == 20 ? "selected" : "" ?>>
                    20 sản phẩm
                </option>

                <option value="30" <?= $limit == 30 ? "selected" : "" ?>>
                    30 sản phẩm
                </option>

            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-semibold">
                Sắp xếp theo
            </label>

            <select name="sort" class="form-select">

                <option
                    value="name_asc"
                    <?= $sort == "name_asc" ? "selected" : "" ?>>
                    Tên A - Z
                </option>

                <option
                    value="name_desc"
                    <?= $sort == "name_desc" ? "selected" : "" ?>>
                    Tên Z - A
                </option>

                <option
                    value="price_asc"
                    <?= $sort == "price_asc" ? "selected" : "" ?>>
                    Giá thấp → cao
                </option>

                <option
                    value="price_desc"
                    <?= $sort == "price_desc" ? "selected" : "" ?>>
                    Giá cao → thấp
                </option>

                <option
                    value="quantity_asc"
                    <?= $sort == "quantity_asc" ? "selected" : "" ?>>
                    Số lượng thấp → cao
                </option>

                <option
                    value="quantity_desc"
                    <?= $sort == "quantity_desc" ? "selected" : "" ?>>
                    Số lượng cao → thấp
                </option>

            </select>
        </div>
        <div class="col-md-3">

            <div class="d-flex gap-2">

                <button
                    type="submit"
                    class="btn btn-primary flex-grow-1">
                    <i class="bi bi-search"></i>
                    Tìm kiếm
                </button>

                <a
                    href="index.php"
                    class="btn btn-secondary"
                    title="Làm mới">
                    <i class="bi bi-arrow-clockwise"></i>
                </a>

            </div>

        </div>

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

                            <form method="POST" action="delete.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $item->id ?>">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                    <i class="bi bi-trash"></i> Xóa
                                </button>
                            </form>


                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php endif; ?>

        </tbody>

    </table>

</div>

<div class="d-flex justify-content-between align-items-center mt-3">

    <form method="GET">

        <label class="me-2">Hiển thị:</label>

        <select
            name="limit"
            class="form-select"
            onchange="this.form.submit()">

            <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>
                10
            </option>

            <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>
                20
            </option>

            <option value="30" <?= $limit == 30 ? 'selected' : '' ?>>
                30
            </option>

        </select>

    </form>

    <?php if ($totalPages > 1): ?>

        <nav>

            <ul class="pagination justify-content-center">

                <?php
                $keywordParam = "";

                if ($keyword != "") {
                    $keywordParam = "&keyword=" . urlencode($keyword);
                }
                ?>

                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">

                    <?php if ($page <= 1): ?>

                        <span class="page-link">
                            Đầu
                        </span>

                    <?php else: ?>

                        <a
                            class="page-link"
                            href="?limit=<?= $limit ?>&page=1<?= $keywordParam ?>">

                            Đầu

                        </a>

                    <?php endif; ?>

                </li>

                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">

                    <?php if ($page <= 1): ?>

                        <span class="page-link">
                            Trước
                        </span>

                    <?php else: ?>

                        <a
                            class="page-link"
                            href="?limit=<?= $limit ?>&page=<?= $page - 1 ?><?= $keywordParam ?>">

                            Trước

                        </a>

                    <?php endif; ?>

                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>

                    <li
                        class="page-item <?= $i == $page ? 'active' : '' ?>">

                        <a
                            class="page-link"
                            href="?limit=<?= $limit ?>&page=<?= $i ?><?= $keywordParam ?>">

                            <?= $i ?>

                        </a>

                    </li>

                <?php endfor; ?>
                <li
                    class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">

                    <?php if ($page >= $totalPages): ?>

                        <span class="page-link">
                            Sau
                        </span>

                    <?php else: ?>

                        <a
                            class="page-link"
                            href="?limit=<?= $limit ?>&page=<?= $page + 1 ?><?= $keywordParam ?>">

                            Sau

                        </a>

                    <?php endif; ?>

                </li>

                <li
                    class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">

                    <?php if ($page >= $totalPages): ?>

                        <span class="page-link">
                            Cuối
                        </span>

                    <?php else: ?>

                        <a
                            class="page-link"
                            href="?limit=<?= $limit ?>&page=<?= $totalPages ?><?= $keywordParam ?>">

                            Cuối

                        </a>

                    <?php endif; ?>

                </li>

            </ul>

        </nav>

    <?php endif; ?>


</div>

</div>

</div>

<?php

$content = ob_get_clean();

include __DIR__ . '/../layouts/master.php';

?>