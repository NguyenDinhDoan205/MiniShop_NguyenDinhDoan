<?php

require_once "../../../dao/ProductDAO.php";

$productDAO = new ProductDAO();

$id = (int)($_GET["id"] ?? 0);

$productId = (int)($_GET["product_id"] ?? 0);

if ($id > 0) {

    $productDAO->deleteImage($id);
}

header("Location: index.php?id=" . $productId);
exit;