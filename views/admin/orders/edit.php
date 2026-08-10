<?php
require_once "../../../dao/OrderDAO.php";
require_once "../../../config/Database.php";

$db = new Database();
$conn = $db->getConnection();

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

$message = "";

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

        $sql = "UPDATE orders
                SET
                    customer_id = ?,
                    user_id = ?,
                    order_code = ?,
                    total_amount = ?,
                    note = ?,
                    status = ?
                WHERE id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "iisdsii",
            $customerId,
            $userId,
            $orderCode,
            $totalAmount,
            $note,
            $status,
            $id
        );

        if ($stmt->execute()) {

            header("Location: index.php");
            exit;

        } else {

            $message = "Cập nhật đơn hàng thất bại.";
        }
    }
}

ob_start();

?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>
            Cập nhập đơn hàng
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
                                value="<?= $customer["id"] ?>"
                                <?= $customer["id"] == $order->customerId
                                    ? "selected"
                                    : "" ?>>

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
                                value="<?= $user["id"] ?>"
                                <?= $user["id"] == $order->userId
                                    ? "selected"
                                    : "" ?>>

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
                        value="<?= htmlspecialchars(
                            $order->orderCode
                        ) ?>"
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
                        value="<?= $order->totalAmount ?>"
                        required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Ghi chú
                    </label>

                    <textarea
                        name="note"
                        class="form-control"
                        rows="3"><?= htmlspecialchars(
                            $order->note ?? ""
                        ) ?></textarea>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Trạng thái
                    </label>

                    <select
                        name="status"
                        class="form-select"
                        required>

                        <option
                            value="0"
                            <?= $order->status == 0
                                ? "selected"
                                : "" ?>>
                            Chờ xử lý
                        </option>

                        <option
                            value="1"
                            <?= $order->status == 1
                                ? "selected"
                                : "" ?>>
                            Đang xử lý
                        </option>

                        <option
                            value="2"
                            <?= $order->status == 2
                                ? "selected"
                                : "" ?>>
                            Hoàn thành
                        </option>

                        <option
                            value="3"
                            <?= $order->status == 3
                                ? "selected"
                                : "" ?>>
                            Đã hủy
                        </option>

                    </select>

                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i>
                    Cập nhật
                </button>

                <a href="index.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Quay lại
                </a>


            </form>

        </div>

    </div>

</div>

<?php

$content = ob_get_clean();

include "../layouts/master.php";

?>

