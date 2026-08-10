<?php
require_once "../../../config/Database.php";

$db = new Database();
$conn = $db->getConnection();

$message = "";
$customers = [];

$sql = "SELECT id, fullname
        FROM customers
        ORDER BY fullname";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $customers[] = $row;
}
$users = [];

$sql = "SELECT id, fullname
        FROM users
        ORDER BY fullname";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $customerId = (int)$_POST["customer_id"];

    $userId = !empty($_POST["user_id"])
        ? (int)$_POST["user_id"]
        : null;

    $orderCode = trim($_POST["order_code"]);

    $totalAmount = (float)$_POST["total_amount"];

    $note = !empty($_POST["note"])
        ? trim($_POST["note"])
        : null;

    $status = (int)$_POST["status"];

    $checkCustomer = $conn->prepare(
        "SELECT id FROM customers WHERE id = ?"
    );

    $checkCustomer->bind_param(
        "i",
        $customerId
    );

    $checkCustomer->execute();

    $customerResult = $checkCustomer->get_result();

    if ($customerResult->num_rows == 0) {

        $message = "Khách hàng không tồn tại.";

    } else {
        $sql = "INSERT INTO orders
                (
                    customer_id,
                    user_id,
                    order_code,
                    total_amount,
                    note,
                    status
                )
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "iisdsi",
            $customerId,
            $userId,
            $orderCode,
            $totalAmount,
            $note,
            $status
        );

        if ($stmt->execute()) {

            header("Location: index.php");
            exit;

        } else {

            $message = "Thêm đơn hàng thất bại.";
        }
    }
}


ob_start();
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>
            Thêm đơn hàng
        </h2>

        

    </div>

    <?php if (!empty($message)): ?>

        <div class="alert alert-danger">

            <i class="bi bi-exclamation-triangle"></i>

            <?= htmlspecialchars($message) ?>

        </div>

    <?php endif; ?>

    <div class="card shadow-sm">

        <div class="card-body">

            <form method="POST">

                <div class="mb-3">

                    <label class="form-label">

                        Khách hàng

                    </label>

                    <select
                        name="customer_id"
                        class="form-select"
                        required>

                        <option value="">
                            -- Chọn khách hàng --
                        </option>

                        <?php foreach ($customers as $customer): ?>

                            <option
                                value="<?= $customer["id"] ?>">

                                <?= htmlspecialchars(
                                    $customer["fullname"]
                                ) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Nhân viên

                    </label>

                    <select
                        name="user_id"
                        class="form-select">

                        <option value="">
                           -- Chọn nhân viên --
                        </option>

                        <?php foreach ($users as $user): ?>

                            <option
                                value="<?= $user["id"] ?>">

                                <?= htmlspecialchars(
                                    $user["fullname"]
                                ) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Mã đơn hàng

                    </label>

                    <input
                        type="text"
                        name="order_code"
                        class="form-control"
                        placeholder="Ví dụ: DH011"
                        required>

                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Tổng tiền

                    </label>

                    <input
                        type="number"
                        name="total_amount"
                        class="form-control"
                        min="0"
                        step="1000"
                        placeholder="Ví dụ: 950000"
                        required>

                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Ghi chú

                    </label>

                    <textarea
                        name="note"
                        class="form-control"
                        rows="3"
                        placeholder="Nhập ghi chú"></textarea>

                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Trạng thái

                    </label>

                    <select
                        name="status"
                        class="form-select"
                        required>

                        <option value="0">

                            Chờ xử lý

                        </option>

                        <option value="1">

                            Đang xử lý

                        </option>

                        <option value="2">

                            Hoàn thành

                        </option>

                        <option value="3">

                            Đã hủy

                        </option>

                    </select>

                </div>

                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="bi bi-save"></i>

                        Lưu đơn hàng

                    </button>


                    <a
                        href="index.php"
                        class="btn btn-secondary">

                        <i class="bi bi-x-circle"></i>

                        Hủy

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>


<?php

$content = ob_get_clean();

include "../layouts/master.php";

?>
