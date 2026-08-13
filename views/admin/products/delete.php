<?php
require_once "../../../dao/ProductDAO.php";
require_once "../../../middleware/CsrfMiddleware.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" && $_SERVER["REQUEST_METHOD"] !== "GET") {
    die("Phương thức không hợp lệ.");
}

$id = (int)($_POST["id"] ?? $_GET["id"] ?? 0);


$id = (int)($_POST["id"] ?? 0);
if ($id <= 0) {
    die("ID sản phẩm không hợp lệ.");
}

$productDAO = new ProductDAO();

if ($productDAO->delete($id)) {
    header("Location: index.php");
    exit;
}

die("Xóa sản phẩm thất bại.");
