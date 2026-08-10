<?php
require_once "../../../dao/OrderDAO.php";

$pageTitle = "Danh sách đơn hàng";

$orderDAO = new OrderDAO();


$page = isset($_GET["page"])
    ? (int)$_GET["page"]
    : 1;

if ($page < 1) {
    $page = 1;
}

$pageSize = 10;

$keyword = isset($_GET["keyword"])
    ? trim($_GET["keyword"])
    : "";

$orders = $orderDAO->getPage(
    $page,
    $pageSize,
    $keyword
);

$total = $orderDAO->count($keyword);

$totalPages = ceil($total / $pageSize);

ob_start();

?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>
          
            Danh sách đơn hàng
        </h2>

        <a
            href="create.php"
            class="btn btn-primary">

            <i class="bi bi-plus-circle"></i>
            Thêm mới

        </a>

    </div>


     <form method="get" class="row g-2 mb-3">
            <div class="col-md-5">
                <input
                    type="text"
                    name="keyword"
                    class="form-control"
                    placeholder="Nhập mã đơn hàng..."
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


    

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>STT</th>
                            <th>Mã đơn hàng</th>
                            <th>Khách hàng</th>
                            <th>Nhân viên</th>
                            <th>Tổng tiền</th>
                            <th>Ghi chú</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th width="250">Thao tác</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php if (empty($orders)): ?>

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center">

                                    Không có đơn hàng

                                </td>

                            </tr>

                        <?php else: ?>


                            <?php foreach ($orders as $order): ?>

                                <tr>

                                    <td>
                                        <?= $order->id ?>
                                    </td>


                                    <td>

                                        <strong>

                                            <?= htmlspecialchars(
                                                $order->orderCode
                                            ) ?>

                                        </strong>

                                    </td>


                                    <td>

                                        <?= $order->customerId ?>

                                    </td>


                                    <td>

                                        <?= $order->userId ?? "Chưa có" ?>

                                    </td>


                                    <td>

                                        <?= number_format(
                                            $order->totalAmount,
                                            0,
                                            ',',
                                            '.'
                                        ) ?>

                                        ₫

                                    </td>


                                    <td>

                                        <?= !empty($order->note)
                                            ? htmlspecialchars($order->note)
                                            : "Không có" ?>

                                    </td>


                                    <td>

                                        <?php if ($order->status == 0): ?>

                                            <span class="badge bg-warning text-dark">

                                                Chờ xử lý

                                            </span>


                                        <?php elseif ($order->status == 1): ?>

                                            <span class="badge bg-primary">

                                                Đang xử lý

                                            </span>


                                        <?php elseif ($order->status == 2): ?>

                                            <span class="badge bg-success">

                                                Hoàn thành

                                            </span>


                                        <?php elseif ($order->status == 3): ?>

                                            <span class="badge bg-danger">

                                                Đã hủy

                                            </span>


                                        <?php else: ?>

                                            <span class="badge bg-secondary">

                                                Không xác định

                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <td>

                                        <?= date(
                                            "d/m/Y H:i",
                                            strtotime($order->createdAt)
                                        ) ?>

                                    </td>


                                    <td class="text-center">

                                        <a
                                            href="detail.php?id=<?= $order->id ?>"
                                            class="btn btn-info btn-sm">

                                            <i class="bi bi-eye"></i>
                                            Chi tiết

                                        </a>


                                        <a
                                            href="edit.php?id=<?= $order->id ?>"
                                            class="btn btn-warning btn-sm">

                                            <i class="bi bi-pencil-square"></i>
                                            Sửa

                                        </a>


                                        <a
                                            href="delete.php?id=<?= $order->id ?>"
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


                        <?php for (
                            $i = 1;
                            $i <= $totalPages;
                            $i++
                        ): ?>

                            <li
                                class="page-item
                                <?= $i == $page
                                    ? "active"
                                    : "" ?>">

                                <a
                                    class="page-link"
                                    href="index.php?page=<?= $i ?>&keyword=<?= urlencode($keyword) ?>">

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
