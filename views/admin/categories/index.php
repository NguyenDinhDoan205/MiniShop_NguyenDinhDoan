<?php
require_once "../../../dao/CategoryDAO.php";

$pageTitle = "Danh sách danh mục";

$categoryDAO = new CategoryDAO();

if (isset($_GET["delete"])) {

    if (!$categoryDAO->delete($_GET["delete"])) {

        echo "<script>
                alert('Không thể xóa vì danh mục đang có sản phẩm.');
                window.location='index.php';
              </script>";

        exit;
    }

    header("Location: index.php");
    exit;
}


$pageSize = 5;
$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;

$keyword = trim($_GET["keyword"] ?? "");

if ($keyword != "") {

    $categories = $categoryDAO->search($keyword);

    $totalPage = 1;

} else {

    $categories = $categoryDAO->paging($page, $pageSize);

    $total = $categoryDAO->count();

    $totalPage = ceil($total / $pageSize);
}

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-3">

    <h1>Danh sách danh mục</h1>

    <a href="create.php" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i>
        Thêm mới
    </a>

</div>

<form method="get" class="row mb-3">

    <div class="col-md-4">

        <input
            type="text"
            name="keyword"
            class="form-control"
            placeholder="Nhập tên danh mục..."
            value="<?= htmlspecialchars($keyword) ?>">

    </div>

    <div class="col-md-2">

        <button class="btn btn-success">

            <i class="bi bi-search"></i>

            Tìm kiếm

        </button>

    </div>

    <?php if($keyword != ""): ?>

    <div class="col-md-2">

        <a href="index.php" class="btn btn-secondary">

            Làm mới

        </a>

    </div>

    <?php endif; ?>

</form>

<table class="table table-bordered table-hover">

    <thead class="table-dark">

        <tr>

            <th width="60">STT</th>

            <th>Tên danh mục</th>

            <th>Slug</th>

            <th width="120">Trạng thái</th>
            <th width="170">Ngày tạo</th>

            <th width="240">Chức năng</th>

        </tr>

    </thead>

    <tbody>

    <?php if(count($categories) > 0): ?>

        <?php foreach($categories as $item): ?>

        <tr>

            <td><?= $item->id ?></td>

            <td><?= $item->catename ?></td>

            <td><?= $item->slug ?></td>

            <td>

                <?php if($item->status == 1): ?>

                    <span class="badge bg-success">

                        Hiển thị

                    </span>

                <?php else: ?>

                    <span class="badge bg-secondary">

                        Ẩn

                    </span>

                <?php endif; ?>

            </td>
            <td><?= $item->created_at ?></td>
            <td>

                <a href="detail.php?id=<?= $item->id ?>"
                    class="btn btn-info btn-sm">

                    <i class="bi bi-eye"></i>

                    Chi tiết

                </a>

                <a href="edit.php?id=<?= $item->id ?>"
                    class="btn btn-warning btn-sm">

                    <i class="bi bi-pencil"></i>

                    Sửa

                </a>

                <a href="?delete=<?= $item->id ?>"
                    class="btn btn-danger btn-sm"
                    onclick="return confirm('Bạn có chắc muốn xóa danh mục này?')">

                    <i class="bi bi-trash"></i>

                    Xóa

                </a>

            </td>

        </tr>

        <?php endforeach; ?>

    <?php else: ?>

        <tr>

            <td colspan="5" class="text-center">

                Không có dữ liệu.

            </td>

        </tr>

    <?php endif; ?>

    </tbody>

</table>

<?php if($keyword == ""): ?>

<nav>

    <ul class="pagination justify-content-center">

        <?php for($i = 1; $i <= $totalPage; $i++): ?>

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

<?php

$content = ob_get_clean();

include "../layouts/master.php";