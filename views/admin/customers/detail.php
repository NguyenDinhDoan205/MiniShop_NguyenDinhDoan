<?php

require_once "../../../dao/CustomerDAO.php";


$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;


$customerDAO = new CustomerDAO();


$customer = $customerDAO->findById($id);


if ($customer == null) {

    echo "Không tìm thấy khách hàng";
    exit;

}


ob_start();

?>


<h2 class="mb-4">Chi tiết khách hàng</h2>

<div class="card">

    <div class="card-header bg-primary text-white">
        Thông tin khách hàng
    </div>


    <div class="card-body">


        <table class="table table-bordered">


            <tr>

                <th width="220">
                    ID
                </th>

                <td>
                    <?= $customer->id ?>
                </td>

            </tr>


            <tr>

                <th>
                    Họ tên
                </th>

                <td>
                    <?= htmlspecialchars($customer->fullname) ?>
                </td>

            </tr>


            <tr>

                <th>
                    Số điện thoại
                </th>

                <td>
                    <?= htmlspecialchars($customer->phone) ?>
                </td>

            </tr>


            <tr>

                <th>
                    Email
                </th>

                <td>
                    <?= htmlspecialchars($customer->email) ?>
                </td>

            </tr>


            <tr>

                <th>
                    Địa chỉ
                </th>

                <td>
                    <?= htmlspecialchars($customer->address) ?>
                </td>

            </tr>


            <tr>

                <th>
                    Ghi chú
                </th>

                <td>
                    <?= nl2br(htmlspecialchars($customer->note)) ?>
                </td>

            </tr>


            <tr>

                <th>
                    Trạng thái
                </th>

                <td>


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

            </tr>


            <tr>

                <th>
                    Ngày tạo
                </th>

                <td>
                    <?= $customer->createdAt ?>
                </td>

            </tr>


            <tr>

                <th>
                    Ngày cập nhật
                </th>

                <td>
                    <?= $customer->updatedAt ?>
                </td>

            </tr>


        </table>



        <a href="edit.php?id=<?= $customer->id ?>"
           class="btn btn-warning">


            <i class="bi bi-pencil-square"></i>

            Cập nhật


        </a>



        <a href="index.php"
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