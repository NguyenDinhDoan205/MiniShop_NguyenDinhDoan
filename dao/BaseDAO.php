<?php

require_once __DIR__ . "/../config/Database.php";

class BaseDAO extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function executeQuery(string $sql): mysqli_result|false
    {
        return $this->conn->query($sql);
    }

    protected function prepare(string $sql): mysqli_stmt|false
    {
        return $this->conn->prepare($sql);
    }

    protected function beginTransaction(): void
    {
        $this->conn->begin_transaction();
    }

    protected function commit(): void
    {
        $this->conn->commit();
    }

    protected function rollback(): void
    {
        $this->conn->rollback();
    }

    public function close(): void
    {
        $this->conn->close();
    }
    public function count(string $table): int
    {
        $sql = "SELECT COUNT(*) AS total FROM $table";

        $result = $this->conn->query($sql);

        $row = $result->fetch_assoc();

        return (int)$row["total"];
    }
}