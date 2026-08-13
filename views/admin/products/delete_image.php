<?php

require_once "../../../dao/ProductDAO.php";
require_once "../../../middleware/CsrfMiddleware.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Phương thức không hợp lệ.");
}

CsrfMiddleware::verify();
$imageId = (int)($_POST["image_id"] ?? 0);

if ($imageId <= 0) {
    die("ID ảnh không hợp lệ.");
}


$productDAO = new ProductDAO();

if ($productDAO->deleteImage($imageId)) {
    $backUrl = $_SERVER["HTTP_REFERER"] ?? "index.php";

    header("Location: " . $backUrl);
    exit;
}

die("Xóa ảnh thất bại.");