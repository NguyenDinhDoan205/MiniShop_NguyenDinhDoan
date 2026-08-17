<?php

namespace Controllers\Admin;

use DAO\ProductDAO;
use DAO\CategoryDAO;
use DAO\BrandDAO;
use Middleware\CsrfMiddleware;

class ProductController
{
    private ProductDAO $productDAO;

    private CategoryDAO $categoryDAO;

    private BrandDAO $brandDAO;

    public function __construct()
    {
        $this->productDAO = new ProductDAO();

        $this->categoryDAO = new CategoryDAO();

        $this->brandDAO = new BrandDAO();
    }

   public function index()
    {
        // Gán dữ liệu cho tiêu đề trang
        $pageTitle = "Danh sách sản phẩm";
        
        $dao = new ProductDAO();

        // Xóa sản phẩm nếu có Request POST
        if (isset($_POST["btnDelete"])) {
            // Yêu cầu Lab 11 không nhắc vụ CSRF ở Controller lúc này, nhưng để nguyên logic cũ
            $dao->delete((int)$_POST["id"]);
        }

        // Đọc request từ URL
        $keyword = trim($_GET["keyword"] ?? "");
        $limit = (int)($_GET["limit"] ?? 10);
        $page = (int)($_GET["page"] ?? 1);
        $sort = trim($_GET["sort"] ?? "");

        // Xử lý offset
        $offset = ($page - 1) * $limit;
        
        // Gọi Dao
        $totalRecords = $dao->count("products", "proname", $keyword);
        $totalPages = ceil($totalRecords / $limit);
        $products = $dao->getPage($limit, $offset, $keyword, $sort);

        // Gọi View
        require __DIR__ . "/../../views/admin/products/index.php";
    }

public function edit(): array
{
    $categoryDAO = new CategoryDAO();
    $brandDAO = new BrandDAO();

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    CsrfMiddleware::generateToken();

    $id = (int)($_GET["id"] ?? 0);

    if ($id <= 0) {
        die("ID sản phẩm không hợp lệ.");
    }

    $product = $this->productDAO->findById($id);

    if ($product === null) {
        die("Không tìm thấy sản phẩm.");
    }

    $categories = $categoryDAO->getAll();

    $brands = $brandDAO->getAll();

    $gallery = $this->productDAO->getImagesByProductId($product->id);

    $errors = [];

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        CsrfMiddleware::verify();

        $product->categoryId = (int)($_POST["categoryId"] ?? 0);
        $product->brandId = (int)($_POST["brandId"] ?? 0);
        $product->proname = trim($_POST["proname"] ?? "");
        $product->slug = trim($_POST["slug"] ?? "");
        $product->price = (float)($_POST["price"] ?? 0);
        $product->discountPrice = (float)($_POST["discountPrice"] ?? 0);
        $product->quantity = (int)($_POST["quantity"] ?? 0);
        $product->description = trim($_POST["description"] ?? "");
        $product->status = (int)($_POST["status"] ?? 1);

        if ($product->categoryId <= 0) {
            $errors["categoryId"] = "Vui lòng chọn danh mục.";
        }

        if ($product->brandId <= 0) {
            $errors["brandId"] = "Vui lòng chọn thương hiệu.";
        }

        if ($product->proname === "") {
            $errors["proname"] = "Tên sản phẩm không được để trống.";
        }

        if ($product->slug === "") {
            $errors["slug"] = "Slug không được để trống.";
        }

        if ($product->price <= 0) {
            $errors["price"] = "Giá phải lớn hơn 0.";
        }

        if ($product->discountPrice < 0) {
            $errors["discountPrice"] = "Giá khuyến mãi không hợp lệ.";
        }

        if ($product->discountPrice > $product->price) {
            $errors["discountPrice"] =
                "Giá khuyến mãi không được lớn hơn giá gốc.";
        }

        if ($product->quantity < 0) {
            $errors["quantity"] = "Số lượng không hợp lệ.";
        }

        if (empty($errors)) {

            if ($this->productDAO->update($product)) {

                header(
                    "Location: /MiniShop_NguyenDinhDoan/index.php?controller=product&action=index"
                );

                exit;
            }

            $errors["general"] = "Cập nhật sản phẩm thất bại.";
        }
    }

return [
    "product" => $product,
    "categories" => $categories,
    "brands" => $brands,
    "gallery" => $gallery,
    "errors" => $errors
];
}

    public function detail(): array
    {
        $id = (int)($_GET["id"] ?? 0);

        if ($id <= 0) {
            die("ID sản phẩm không hợp lệ.");
        }

        $product = $this->productDAO->findById($id);

        if ($product === null) {
            die("Không tìm thấy sản phẩm.");
        }

        $gallery = $this->productDAO->getImagesByProductId(
            $id
        );

        return [
            "product" => $product,
            "gallery" => $gallery
        ];
    }

    public function delete()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            die("Phương thức không hợp lệ.");
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        CsrfMiddleware::verify();

        $id = (int)($_POST["id"] ?? 0);

        if ($id <= 0) {
            die("ID sản phẩm không hợp lệ.");
        }

        $this->productDAO->delete($id);

        header(
            "Location: /MiniShop_NguyenDinhDoan/index.php?controller=product&action=index"
        );

        exit;
    }
}