<?php

require_once __DIR__ . "/../../dao/ProductDAO.php";
require_once __DIR__ . "/../../models/Product.php";
require_once __DIR__ . "/../../middleware/RoleMiddleware.php";

class ProductController
{
    public function index()
    {

        RoleMiddleware::requireRole(1);


        $pageTitle = "Danh sách Sản phẩm";


        $productDAO = new ProductDAO();
        $limit = (int)($_GET["limit"] ?? 10);

        if (
            $limit != 10 &&
            $limit != 20 &&
            $limit != 30
        ) {
            $limit = 10;
        }


        $page = (int)($_GET["page"] ?? 1);

        if ($page < 1) {
            $page = 1;
        }


        $keyword = trim($_GET["keyword"] ?? "");


        $sort = $_GET["sort"] ?? "name_asc";


        $allowedSort = [
            "name_asc",
            "name_desc",
            "price_asc",
            "price_desc",
            "quantity_asc",
            "quantity_desc"
        ];


        if (!in_array($sort, $allowedSort, true)) {
            $sort = "name_asc";
        }

        $offset = ($page - 1) * $limit;


        if ($keyword != "") {

            $products = $productDAO->search($keyword);

            $totalRecords = count($products);

            $totalPages = $totalRecords > 0
                ? (int)ceil($totalRecords / $limit)
                : 1;


            if ($page > $totalPages) {
                $page = $totalPages;

                $offset = ($page - 1) * $limit;
            }


            $products = array_slice(
                $products,
                $offset,
                $limit
            );

        } else {

            $totalRecords = $productDAO->count("products");

            $totalPages = $totalRecords > 0
                ? (int)ceil($totalRecords / $limit)
                : 1;


            if ($page > $totalPages) {
                $page = $totalPages;

                $offset = ($page - 1) * $limit;
            }


            $products = $productDAO->getPage(
                $limit,
                $offset
            );
        }

        require __DIR__ . "/../../views/admin/products/index.php";
    }
}