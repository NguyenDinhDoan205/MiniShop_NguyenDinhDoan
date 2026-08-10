<?php
require_once "../../../dao/OrderDAO.php";

$orderDAO = new OrderDAO();
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

$order = $orderDAO->findById($id);
if ($order == null) {

    header("Location: index.php");
    exit;

}


ob_start();

?>


<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
           

            Chi tiết đơn hàng

        </h2>

        
    </div>

    <div class="card shadow-sm">


        <div class="card-header bg-primary text-white">

            <i class="bi bi-info-circle"></i>

            Thông tin đơn hàng

        </div>


        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">

                    <label class="fw-bold">
                        ID:
                    </label>

                    <div class="form-control bg-light">

                        <?= $order->id ?>

                    </div>

                </div>
                <div class="col-md-6 mb-3">

                    <label class="fw-bold">
                        Mã đơn hàng:
                    </label>

                    <div class="form-control bg-light">

                        <?= htmlspecialchars(
                            $order->orderCode
                        ) ?>

                    </div>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="fw-bold">
                        Khách hàng:
                    </label>

                    <div class="form-control bg-light">

                        <?= $order->customerId ?>

                    </div>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="fw-bold">
                        Nhân viên:
                    </label>

                    <div class="form-control bg-light">

                        <?= $order->userId ?? "Chưa có" ?>

                    </div>

                </div>
                <div class="col-md-6 mb-3">

                    <label class="fw-bold">
                        Tổng tiền:
                    </label>

                    <div class="form-control bg-light text-danger fw-bold">

                        <?= number_format(
                            $order->totalAmount,
                            0,
                            ',',
                            '.'
                        ) ?> ₫

                    </div>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="fw-bold">
                        Trạng thái:
                    </label>

                    <div class="form-control bg-light">

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

                                <?= $order->status ?>

                            </span>

                        <?php endif; ?>

                    </div>

                </div>

                <div class="col-md-12 mb-3">

                    <label class="fw-bold">
                        Ghi chú:
                    </label>

                    <div class="form-control bg-light">

                        <?= !empty($order->note)
                            ? htmlspecialchars($order->note)
                            : "Không có ghi chú" ?>

                    </div>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="fw-bold">
                        Ngày tạo:
                    </label>

                    <div class="form-control bg-light">

                        <?= date(
                            "d/m/Y H:i",
                            strtotime($order->createdAt)
                        ) ?>

                    </div>

                </div>
                <div class="col-md-6 mb-3">

                    <label class="fw-bold">
                        Ngày cập nhật:
                    </label>

                    <div class="form-control bg-light">

                        <?= !empty($order->updatedAt)
                            ? date(
                                "d/m/Y H:i",
                                strtotime($order->updatedAt)
                            )
                            : "Chưa cập nhật" ?>

                    </div>

                </div>


            </div>


        </div>

    </div>
    <div class="mt-3">
          <a
            href="edit.php?id=<?= $order->id ?>"
            class="btn btn-warning">

            <i class="bi bi-pencil-square"></i>

            Chỉnh sửa

        </a>


        <a
            href="index.php"
            class="btn btn-secondary">

            <i class="bi bi-arrow-left"></i>

            Quay lại 

        </a>


      
    </div>


</div>


<?php

$content = ob_get_clean();

include "../layouts/master.php";

?>
