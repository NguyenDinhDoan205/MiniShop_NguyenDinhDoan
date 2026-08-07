<?php

require_once "../../../dao/CustomerDAO.php";


$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$customerDAO = new CustomerDAO();
$customer = $customerDAO->findById($id);

if ($customer == null) {

    echo "Không tìm thấy khách hàng";
    exit;

}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $fullname = trim($_POST["fullname"]);
    $phone = trim($_POST["phone"]);
    $email = trim($_POST["email"]);
    $address = trim($_POST["address"]);
    $note = trim($_POST["note"]);
    $status = (int)$_POST["status"];
    if ($fullname == "") {
        $errors["fullname"] = "Vui lòng nhập họ tên";

    }
    if ($phone == "") {
        $errors["phone"] = "Vui lòng nhập số điện thoại";
    }
    if ($email != "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $errors["email"] = "Email không hợp lệ";

    }

    if (empty($errors)) {

        $customer->fullname = $fullname;
        $customer->phone = $phone;
        $customer->email = $email;
        $customer->address = $address;
        $customer->note = $note;
        $customer->status = $status;
        if ($customerDAO->update($customer)) {
            header("Location: index.php");

            exit;

        }
    }

}



ob_start();

?>


<h2 class="mb-4">Cập nhật khách hàng</h2>
    <div class="card-body">
        <form method="post">
            <div class="mb-3">
                <label class="form-label">

                    Họ và tên

                </label>


                <input
                    type="text"
                    name="fullname"
                    class="form-control"
                    value="<?= htmlspecialchars($customer->fullname) ?>">


                <small class="text-danger">

                    <?= $errors["fullname"] ?? "" ?>

                </small>


            </div>




            <div class="mb-3">


                <label class="form-label">

                    Số điện thoại

                </label>


                <input
                    type="text"
                    name="phone"
                    class="form-control"
                    value="<?= htmlspecialchars($customer->phone) ?>">


                <small class="text-danger">

                    <?= $errors["phone"] ?? "" ?>

                </small>


            </div>

            <div class="mb-3">
                <label class="form-label">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="<?= htmlspecialchars($customer->email) ?>">


                <small class="text-danger">

                    <?= $errors["email"] ?? "" ?>

                </small>


            </div>




            <div class="mb-3">


                <label class="form-label">

                    Địa chỉ

                </label>


                <input
                    type="text"
                    name="address"
                    class="form-control"
                    value="<?= htmlspecialchars($customer->address) ?>">


            </div>




            <div class="mb-3">


                <label class="form-label">

                    Ghi chú

                </label>


                <textarea
                    name="note"
                    rows="4"
                    class="form-control"><?= htmlspecialchars($customer->note) ?></textarea>


            </div>

            <div class="mb-3">
                <label class="form-label">
                    Trạng thái

                </label>
                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="radio"
                        name="status"
                        value="1"
                        <?= $customer->status == 1 ? "checked" : "" ?>>


                    <label class="form-check-label">

                        Hoạt động

                    </label>


                </div>




                <div class="form-check">


                    <input
                        class="form-check-input"
                        type="radio"
                        name="status"
                        value="0"
                        <?= $customer->status == 0 ? "checked" : "" ?>>


                    <label class="form-check-label">

                        Khóa

                    </label>


                </div>


            </div>




            <button class="btn btn-primary">

                <i class="bi bi-save"></i>

                Cập nhật

            </button>



            <a href="index.php" class="btn btn-secondary">

                Quay lại

            </a>



        </form>


    </div>


</div>



<?php

$content = ob_get_clean();

include "../layouts/master.php";

?>