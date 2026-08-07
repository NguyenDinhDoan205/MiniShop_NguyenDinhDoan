<?php
require_once "../../../dao/CustomerDAO.php";

$pageTitle = "Quản lý khách hàng";

$customerDAO = new CustomerDAO();

$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
$pageSize = 5;

$keyword = trim($_GET["keyword"] ?? "");

if ($keyword != "") {
    $customers = $customerDAO->search($keyword);
    $total = count($customers);
    $totalPage = 1;
} else {
    $customers = $customerDAO->paging($page, $pageSize);
    $total = $customerDAO->count();
    $totalPage = ceil($total / $pageSize);
}

ob_start();
?>
<div class="d-flex justify-content-between align-items-center">

            <h2>
                Danh sách khách hàng
            </h2>

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
                    placeholder="Nhập tên hoặc số điện thoại..."
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
                        <th>Họ tên</th>
                        <th>SĐT</th>
                        <th>Email</th>
                        <th>Địa chỉ</th>
                        <th width="100">Trạng thái</th>
                        <th width="240">Thao tác</th>

                    </tr>

                </thead>

                <tbody>

<?php
if (count($customers) == 0):
?>

<tr>
    <td colspan="7" class="text-center text-danger">
        Không có dữ liệu.
    </td>
</tr>

<?php
else:

$stt = ($page - 1) * $pageSize + 1;

foreach ($customers as $customer):
?>

<tr>

    <td class="text-center">
        <?= $stt++ ?>
    </td>

    <td>
        <strong><?= htmlspecialchars($customer->fullname) ?></strong>
    </td>

    <td>
        <?= htmlspecialchars($customer->phone) ?>
    </td>

    <td>
        <?= htmlspecialchars($customer->email) ?>
    </td>

    <td>
        <?= htmlspecialchars($customer->address) ?>
    </td>

    <td class="text-center">

        <?php if ($customer->status == 1): ?>

            <span class="badge bg-success">
                Hoạt động
            </span>

        <?php else: ?>

            <span class="badge bg-danger">
                Khóa
            </span>

        <?php endif; ?>

    </td>

    <td class="text-center">

        <a href="detail.php?id=<?= $customer->id ?>"
           class="btn btn-info btn-sm">

            <i class="bi bi-eye"></i>
            Chi tiết

        </a>

        <a href="edit.php?id=<?= $customer->id ?>"
           class="btn btn-warning btn-sm">

            <i class="bi bi-pencil-square"></i>
            Sửa

        </a>

        <a href="delete.php?id=<?= $customer->id ?>"
           class="btn btn-danger btn-sm"
           onclick="return confirm('Bạn có chắc chắn muốn xóa?')">

            <i class="bi bi-trash"></i>
            Xóa

        </a>

    </td>

</tr>

<?php endforeach; endif; ?>
                </tbody>

            </table>

        </div>

        <?php if ($keyword == "" && $totalPage > 1): ?>

            <nav class="mt-3">

                <ul class="pagination justify-content-center">

                    <?php if ($page > 1): ?>

                        <li class="page-item">

                            <a class="page-link"
                               href="?page=<?= $page - 1 ?>">

                                <i class="bi bi-chevron-left"></i>

                            </a>

                        </li>

                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPage; $i++): ?>

                        <li class="page-item <?= ($page == $i) ? "active" : "" ?>">

                            <a class="page-link"
                               href="?page=<?= $i ?>">

                                <?= $i ?>

                            </a>

                        </li>

                    <?php endfor; ?>

                    <?php if ($page < $totalPage): ?>

                        <li class="page-item">

                            <a class="page-link"
                               href="?page=<?= $page + 1 ?>">

                                <i class="bi bi-chevron-right"></i>

                            </a>

                        </li>

                    <?php endif; ?>

                </ul>

            </nav>

        <?php endif; ?>

    </div>

</div>

<?php

$content = ob_get_clean();

include "../layouts/master.php";

?>