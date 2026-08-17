<?php

namespace Controllers\Admin;

use DAO\ProductDAO;
use DAO\CategoryDAO;
use DAO\BrandDAO;
use Models\Product;
use Middleware\RoleMiddleware;
use Middleware\CsrfMiddleware;

class ProductController
{
    protected ProductDAO $productDAO;
    protected CategoryDAO $categoryDAO;
    protected BrandDAO $brandDAO;
    protected string $uploadDir;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->productDAO = new ProductDAO();
        $this->categoryDAO = new CategoryDAO();
        $this->brandDAO = new BrandDAO();

        $this->uploadDir = __DIR__ . "/../../uploads/";

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    /**
     * Danh sách sản phẩm
     */
    public function index(): array
    {
        RoleMiddleware::requireRole(1);

        // ==============================
        // Tìm kiếm
        // ==============================
        $keyword = trim($_GET["keyword"] ?? "");

        // ==============================
        // Phân trang
        // ==============================
        $limit = (int)($_GET["limit"] ?? 10);

        if (!in_array($limit, [10, 20, 30], true)) {
            $limit = 10;
        }

        $page = (int)($_GET["page"] ?? 1);

        if ($page < 1) {
            $page = 1;
        }

        // ==============================
        // Sắp xếp
        // ==============================
        $sort = $_GET["sort"] ?? "name_asc";

        $allowedSort = [
            "name_asc",
            "name_desc",
            "price_asc",
            "price_desc",
            "quantity_asc",
            "quantity_desc",
            "newest",
            "oldest"
        ];

        if (!in_array($sort, $allowedSort, true)) {
            $sort = "name_asc";
        }

        // ==============================
        // Offset
        // ==============================
        $offset = ($page - 1) * $limit;

        // ==============================
        // Lấy tổng số sản phẩm
        // ==============================
        $totalProducts = $this->productDAO->count(
            "products",
            "",
            $keyword
        );

        // ==============================
        // Tổng số trang
        // ==============================
        $totalPages = (int)ceil($totalProducts / $limit);

        if ($totalPages > 0 && $page > $totalPages) {
            $page = $totalPages;
            $offset = ($page - 1) * $limit;
        }

        // ==============================
        // Lấy danh sách sản phẩm
        // ==============================
        $products = $this->productDAO->getPage(
            $limit,
            $offset,
            $keyword,
            $sort
        );

        // ==============================
        // Debug nếu cần
        // ==============================
        // var_dump($products);
        // exit;

        return [
            "pageTitle"     => "Quản lý sản phẩm",
            "products"      => $products,
            "keyword"       => $keyword,
            "limit"         => $limit,
            "page"          => $page,
            "totalProducts" => $totalProducts,
            "totalPages"    => $totalPages,
            "sort"          => $sort
        ];
    }

    /**
     * Thêm sản phẩm
     */
    public function create(): array
    {
        RoleMiddleware::requireRole(1);

        $categories = $this->categoryDAO->getAll();
        $brands = $this->brandDAO->getAll();

        $error = "";
        $postData = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            CsrfMiddleware::verify();

            $postData = $_POST;

            $categoryId = (int)($_POST["category_id"] ?? 0);
            $brandId = (int)($_POST["brand_id"] ?? 0);
            $proname = trim($_POST["proname"] ?? "");
            $price = (float)($_POST["price"] ?? 0);
            $discountPrice = (float)($_POST["discount_price"] ?? 0);
            $quantity = (int)($_POST["quantity"] ?? 0);
            $description = trim($_POST["description"] ?? "");
            $status = (int)($_POST["status"] ?? 1);

            // ==============================
            // Tạo slug
            // ==============================
            $slug = strtolower(trim($proname));
            $slug = preg_replace('/[^a-zA-Z0-9]+/', '-', $slug);
            $slug = trim($slug, '-');
            $slug .= "-" . time();

            // ==============================
            // Validate
            // ==============================
            if ($proname === "") {
                $error = "Tên sản phẩm không được để trống.";
            } elseif ($categoryId <= 0) {
                $error = "Vui lòng chọn danh mục.";
            } elseif ($brandId <= 0) {
                $error = "Vui lòng chọn thương hiệu.";
            } elseif ($price <= 0) {
                $error = "Giá sản phẩm phải lớn hơn 0.";
            } elseif ($quantity < 0) {
                $error = "Số lượng không hợp lệ.";
            }

            // ==============================
            // Upload ảnh chính
            // ==============================
            $image = "";

            if ($error === "") {
                $image = $this->handleImageUpload("image");
            }

            // ==============================
            // Insert
            // ==============================
            if ($error === "") {

                $product = new Product(
                    $categoryId,
                    $brandId,
                    $proname,
                    $slug,
                    $price,
                    $discountPrice,
                    $quantity,
                    $image,
                    $description,
                    $status
                );

                $productId = $this->productDAO->insert($product);

                if ($productId > 0) {

                    // Upload gallery
                    $this->handleGalleryUpload($productId);

                    header(
                        "Location: /MiniShop_NguyenDinhDoan/index.php?controller=product&action=index&success=1"
                    );
                    exit;

                } else {
                    $error = "Không thể thêm sản phẩm.";
                }
            }
        }

        return [
            "pageTitle"  => "Thêm sản phẩm",
            "categories" => $categories,
            "brands"     => $brands,
            "error"      => $error,
            "postData"   => $postData
        ];
    }
    public function detail(): array
{
    RoleMiddleware::requireRole(1);

    $id = (int)($_GET["id"] ?? 0);

    if ($id <= 0) {
        die("ID sản phẩm không hợp lệ.");
    }

    $product = $this->productDAO->findById($id);

    if ($product === null) {
        die("Không tìm thấy sản phẩm.");
    }

    $gallery = $this->productDAO->getImagesByProductId($id);

    return [
        "pageTitle" => "Chi tiết sản phẩm",
        "product"   => $product,
        "gallery"   => $gallery
    ];
}

    /**
     * Chỉnh sửa sản phẩm
     */
    public function edit(): array
    {
        RoleMiddleware::requireRole(1);

        $id = (int)($_GET["id"] ?? 0);

        $product = $this->productDAO->findById($id);

        if ($product === null) {
            header(
                "Location: /MiniShop_NguyenDinhDoan/index.php?controller=product&action=index&error=notfound"
            );
            exit;
        }

        $categories = $this->categoryDAO->getAll();
        $brands = $this->brandDAO->getAll();

        $gallery = $this->productDAO->getImagesByProductId(
            $product->id
        );

        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            CsrfMiddleware::verify();

            $categoryId = (int)($_POST["categoryId"] ?? 0);
            $brandId = (int)($_POST["brandId"] ?? 0);
            $proname = trim($_POST["proname"] ?? "");
            $slug = trim($_POST["slug"] ?? "");
            $price = (float)($_POST["price"] ?? 0);
            $discountPrice = (float)($_POST["discountPrice"] ?? 0);
            $quantity = (int)($_POST["quantity"] ?? 0);
            $description = trim($_POST["description"] ?? "");
            $status = (int)($_POST["status"] ?? 1);

            // ==============================
            // Validate
            // ==============================
            if ($proname === "") {
                $errors["proname"] =
                    "Tên sản phẩm không được để trống.";
            }

            if ($categoryId <= 0) {
                $errors["categoryId"] =
                    "Vui lòng chọn danh mục.";
            }

            if ($brandId <= 0) {
                $errors["brandId"] =
                    "Vui lòng chọn thương hiệu.";
            }

            if ($price <= 0) {
                $errors["price"] =
                    "Giá sản phẩm phải lớn hơn 0.";
            }

            if ($quantity < 0) {
                $errors["quantity"] =
                    "Số lượng không hợp lệ.";
            }

            // ==============================
            // Ảnh chính
            // ==============================
            $image = $product->image;

            if (
                isset($_FILES["image"]) &&
                $_FILES["image"]["error"] === 0
            ) {

                $newImage =
                    $this->handleImageUpload("image");

                if ($newImage !== "") {

                    if (!empty($product->image)) {

                        $oldPath =
                            $this->uploadDir .
                            $product->image;

                        if (is_file($oldPath)) {
                            unlink($oldPath);
                        }
                    }

                    $image = $newImage;

                } else {

                    $errors["image"] =
                        "Không thể tải lên ảnh mới.";
                }
            }

            // ==============================
            // Update
            // ==============================
            if (empty($errors)) {

                $product->categoryId = $categoryId;
                $product->brandId = $brandId;
                $product->proname = $proname;
                $product->slug = $slug;
                $product->price = $price;
                $product->discountPrice = $discountPrice;
                $product->quantity = $quantity;
                $product->image = $image;
                $product->description = $description;
                $product->status = $status;

                $updated =
                    $this->productDAO->update($product);

                if ($updated) {

                    $this->handleGalleryUpload(
                        $product->id
                    );

                    header(
                        "Location: /MiniShop_NguyenDinhDoan/index.php?controller=product&action=index&success=updated"
                    );
                    exit;

                } else {

                    $errors["general"] =
                        "Không thể cập nhật sản phẩm.";
                }

            } else {

                $product->categoryId = $categoryId;
                $product->brandId = $brandId;
                $product->proname = $proname;
                $product->slug = $slug;
                $product->price = $price;
                $product->discountPrice = $discountPrice;
                $product->quantity = $quantity;
                $product->description = $description;
                $product->status = $status;
            }
        }

        return [
            "pageTitle"  => "Chỉnh sửa sản phẩm",
            "product"    => $product,
            "categories" => $categories,
            "brands"     => $brands,
            "gallery"    => $gallery,
            "errors"     => $errors
        ];
    }

    /**
     * Xóa ảnh gallery
     */
    public function deleteImage(): void
    {
        RoleMiddleware::requireRole(1);

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            CsrfMiddleware::verify();

            $imageId =
                (int)($_POST["image_id"] ?? 0);

            $image =
                $this->productDAO->findImageById(
                    $imageId
                );

            if ($image !== null) {

                $path =
                    $this->uploadDir .
                    $image["image"];

                if (is_file($path)) {
                    unlink($path);
                }

                $this->productDAO->deleteImage(
                    $imageId
                );
            }
        }

        $productId =
            (int)($_POST["product_id"] ?? 0);

        header(
            "Location: /MiniShop_NguyenDinhDoan/index.php?controller=product&action=edit&id={$productId}"
        );
        exit;
    }

    /**
     * Upload ảnh chính
     */
    private function handleImageUpload(
        string $inputName
    ): string {

        if (
            !isset($_FILES[$inputName]) ||
            $_FILES[$inputName]["error"] != 0
        ) {
            return "";
        }

        $imageName =
            time() . "_" .
            basename($_FILES[$inputName]["name"]);

        $target =
            $this->uploadDir . $imageName;

        if (
            move_uploaded_file(
                $_FILES[$inputName]["tmp_name"],
                $target
            )
        ) {
            return $imageName;
        }

        return "";
    }

    private function handleGalleryUpload(
        int $productId
    ): void {

        if (!isset($_FILES["images"])) {
            return;
        }

        foreach (
            $_FILES["images"]["name"]
            as $key => $name
        ) {

            if (
                empty($name) ||
                $_FILES["images"]["error"][$key] != 0
            ) {
                continue;
            }

            $imageName =
                time() . "_" .
                $key . "_" .
                basename($name);

            $target =
                $this->uploadDir . $imageName;

            if (
                move_uploaded_file(
                    $_FILES["images"]["tmp_name"][$key],
                    $target
                )
            ) {

                $this->productDAO->insertImage(
                    $productId,
                    $imageName
                );
            }
        }
    }
}