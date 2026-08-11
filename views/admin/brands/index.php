<?php

require_once "../../../dao/BrandDAO.php";

$pageTitle = "Quản lý thương hiệu";

$brandDAO = new BrandDAO();

if (isset($_GET["delete"])) {

    $id = (int)$_GET["delete"];

    $brandDAO->delete($id);

    header("Location: index.php");
    exit;
}

$keyword = trim($_GET["keyword"] ?? "");

$pageSize = 5;

$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;

if ($page < 1) {
    $page = 1;
}

if ($keyword != "") {

    $brands = $brandDAO->search($keyword);

    $totalRecords = count($brands);

    $totalPage = (int)ceil($totalRecords / $pageSize);

    if ($totalPage > 0 && $page > $totalPage) {
        $page = $totalPage;
    }

    $offset = ($page - 1) * $pageSize;

    $brands = array_slice(
        $brands,
        $offset,
        $pageSize
    );

} else {

    $totalRecords = $brandDAO->count("brands");

    $totalPage = (int)ceil($totalRecords / $pageSize);

    if ($totalPage > 0 && $page > $totalPage) {
        $page = $totalPage;
    }

    $brands = $brandDAO->paging(
        $page,
        $pageSize
    );
}

ob_start();
?>



<div class="d-flex justify-content-between align-items-center mb-3">

    <h2>Danh sách thương hiệu</h2>

    <a href="create.php" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i>
        Thêm mới
    </a>

</div>

<form method="get" class="row mb-3">

    <div class="col-md-4">

        <input
            type="text"
            class="form-control"
            name="keyword"
            value="<?= $keyword ?>"
            placeholder="Nhập tên thương hiệu">

    </div>

    <div class="col-md-2">

        <button class="btn btn-success">

            <i class="bi bi-search"></i>

            Tìm kiếm

        </button>

    </div>

    <?php if ($keyword != ""): ?>

    <div class="col-md-2">

        <a href="index.php" class="btn btn-secondary">

            Làm mới

        </a>

    </div>

    <?php endif; ?>

</form>

<table class="table table-bordered table-hover align-middle">

    <thead class="table-dark">

        <tr>

            <th width="60">STT</th>

            <th>Tên thương hiệu</th>

            <th>Slug</th>

            <th width="240">Hình ảnh</th>

            <th width="120">Trạng thái</th>
            <th width="170">Ngày tạo</th>

            <th width="240">Chức năng</th>

        </tr>

    </thead>

    <tbody>

    <?php
    $stt = ($page - 1) * $pageSize + 1;

    foreach ($brands as $item):
    ?>

        <tr>

            <td><?= $stt++ ?></td>

            <td><?= $item->brandname ?></td>

            <td><?= $item->slug ?></td>

            <td width="120">

                <?php if (!empty($item->image)): ?>

                    <img
                        src="../../../uploads/brands/<?= $item->image ?>"
                        width="80"
                        class="img-thumbnail">

                <?php else: ?>

                    Không có ảnh

                <?php endif; ?>

            </td>

            <td>

                <?php if ($item->status == 1): ?>

                    <span class="badge bg-success">

                        Hoạt động

                    </span>

                <?php else: ?>

                    <span class="badge bg-danger">

                        Ngừng

                    </span>

                <?php endif; ?>

            </td>

            <td><?= $item->created_at ?></td>

            <td>

                <a
                    href="detail.php?id=<?= $item->id ?>"
                    class="btn btn-info btn-sm">

                    <i class="bi bi-eye">Chi tiết</i>

                </a>

                <a
                    href="edit.php?id=<?= $item->id ?>"
                    class="btn btn-warning btn-sm">

                    <i class="bi bi-pencil">Sửa</i>

                </a>

                <a
                    href="?delete=<?= $item->id ?>"
                    class="btn btn-danger btn-sm"
                    onclick="return confirm('Bạn có chắc muốn xóa?')">

                    <i class="bi bi-trash">Xóa</i>

                </a>

            </td>

        </tr>

    <?php endforeach; ?>

    </tbody>

</table>

<?php if ($keyword == ""): ?>

<nav>

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

<?php

$content = ob_get_clean();

include "../layouts/master.php";