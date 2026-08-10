<?php
require_once __DIR__ . "/BaseDAO.php";
require_once __DIR__ . "/../models/Order.php";

class OrderDAO extends BaseDAO
{
    public function __construct()
    {
        parent::__construct();
    }


    public function getAll(): array
    {
        $list = [];

        try {
            $sql = "SELECT * FROM orders ORDER BY created_at DESC";
            $result = $this->executeQuery($sql);

            while ($row = $result->fetch_assoc()) {
                $order = new Order(
                    $row["customer_id"],
                    $row["user_id"],
                    $row["order_code"],
                    $row["total_amount"],
                    $row["note"],
                    $row["status"]
                );

                $order->id = $row["id"];
                $order->createdAt = $row["created_at"];
                $order->updatedAt = $row["updated_at"];

                $list[] = $order;
            }
        } catch (Exception $e) {
            throw $e;
        }

        return $list;
    }
    public function latest(int $limit = 5): array
    {
        $list = [];

        $sql = "SELECT * FROM orders
                ORDER BY created_at DESC
                LIMIT ?";

        $stmt = $this->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {

            $order = new Order(
                $row["customer_id"],
                $row["user_id"],
                $row["order_code"],
                $row["total_amount"],
                $row["status"]
            );

            $order->id = $row["id"];

            $list[] = $order;
        }

        return $list;
    }

    public function findById(int $id): ?Order
    {
        try {
            $sql = "SELECT * FROM orders WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {

                $order = new Order(
                    $row["customer_id"],
                    $row["user_id"],
                    $row["order_code"],
                    $row["total_amount"],
                    $row["note"],
                    $row["status"]
                );

                $order->id = $row["id"];
                $order->createdAt = $row["created_at"];
                $order->updatedAt = $row["updated_at"];

                return $order;
            }
        } catch (Exception $e) {
            throw $e;
        }

        return null;
    }
     public function getPage(int $page, int $pageSize): array
    {
        $list = [];

        $offset = ($page - 1) * $pageSize;

        $sql = "SELECT *
                FROM orders
                ORDER BY created_at DESC
                LIMIT ?, ?";

        $stmt = $this->prepare($sql);

        $stmt->bind_param("ii", $offset, $pageSize);

        $stmt->execute();

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {

            $order = new Order(
                (int)$row["customer_id"],
                $row["user_id"] !== null
                    ? (int)$row["user_id"]
                    : null,
                $row["order_code"],
                (float)$row["total_amount"],
                $row["note"],
                (int)$row["status"]
            );

            $order->id = (int)$row["id"];
            $order->createdAt = $row["created_at"];
            $order->updatedAt = $row["updated_at"];

            $list[] = $order;
        }

        return $list;
    }
    public function count(): int
    {
        $sql = "SELECT COUNT(*) AS total FROM orders";
        $result = $this->executeQuery($sql);
        $row = $result->fetch_assoc();

        return (int)$row["total"];
    }


    public function insert(Order $order): bool
    {
        try {
            $sql = "INSERT INTO orders
                    (customer_id, user_id, order_code, total_amount, note, status)
                    VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $this->prepare($sql);

            $stmt->bind_param(
                "iisdsi",
                $order->customerId,
                $order->userId,
                $order->orderCode,
                $order->totalAmount,
                $order->note,
                $order->status
            );

            return $stmt->execute();

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(Order $order): bool
    {
        try {

            $sql = "UPDATE orders
                    SET
                        customer_id=?,
                        user_id=?,
                        order_code=?,
                        total_amount=?,
                        note=?,
                        status=?
                    WHERE id=?";

            $stmt = $this->prepare($sql);

            $stmt->bind_param(
                "iisdsii",
                $order->customerId,
                $order->userId,
                $order->orderCode,
                $order->totalAmount,
                $order->note,
                $order->status,
                $order->id
            );

            return $stmt->execute();

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        try {

            $sql = "DELETE FROM orders WHERE id=?";

            $stmt = $this->prepare($sql);

            $stmt->bind_param("i", $id);

            return $stmt->execute();

        } catch (Exception $e) {
            throw $e;
        }
    }
}