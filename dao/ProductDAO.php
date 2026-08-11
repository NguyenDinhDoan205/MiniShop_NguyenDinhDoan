<?php
require_once __DIR__ . "/BaseDAO.php";
require_once __DIR__ . "/../models/Product.php";

class ProductDAO extends BaseDAO
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll(): array
    {
        $list = [];

        try {
            $sql = "SELECT * FROM products ORDER BY proname";
            $result = $this->executeQuery($sql);

            while ($row = $result->fetch_assoc()) {

                $product = new Product(
                    $row["category_id"],
                    $row["brand_id"],
                    $row["proname"],
                    $row["slug"],
                    $row["price"],
                    $row["discount_price"],
                    $row["quantity"],
                    $row["image"],
                    $row["description"],
                    $row["status"]
                );

                $product->id = $row["id"];
                $product->createdAt = $row["created_at"];
                $product->updatedAt = $row["updated_at"];

                $list[] = $product;
            }

        } catch (Exception $e) {
            throw $e;
        }

        return $list;
    }
    public function latest(int $limit = 5): array
    {
        $list = [];

        $sql = "SELECT * FROM products
                ORDER BY created_at DESC
                LIMIT ?";

        $stmt = $this->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {

            $product = new Product(
                $row["category_id"],
                $row["brand_id"],
                $row["proname"],
                $row["slug"],
                $row["price"],
                $row["discount_price"],
                $row["quantity"],
                $row["image"],
                $row["description"],
                $row["status"]
            );

            $product->id = $row["id"];

            $list[] = $product;
        }

        return $list;
    }
   

   public function findById(int $id): ?Product
    {
        try {

            $sql = "SELECT 
                        p.*,
                        c.catename AS catename,
                        b.brandname AS brandname
                    FROM products p
                    LEFT JOIN categories c 
                        ON p.category_id = c.id
                    LEFT JOIN brands b 
                        ON p.brand_id = b.id
                    WHERE p.id = ?";

            $stmt = $this->prepare($sql);

            $stmt->bind_param("i", $id);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {

                $product = new Product(
                    $row["category_id"],
                    $row["brand_id"],
                    $row["proname"],
                    $row["slug"],
                    $row["price"],
                    $row["discount_price"],
                    $row["quantity"],
                    $row["image"],
                    $row["description"],
                    $row["status"]
                );

                $product->id = $row["id"];
                $product->catename = $row["catename"] ?? "";
                $product->brandname = $row["brandname"] ?? "";
                $product->createdAt = $row["created_at"];
                $product->updatedAt = $row["updated_at"];

                return $product;
            }

        } catch (Exception $e) {
            throw $e;
        }

        return null;
    }


    public function insert(Product $product): int
    {
        $sql = "INSERT INTO products
                (
                    category_id,
                    brand_id,
                    proname,
                    slug,
                    price,
                    discount_price,
                    quantity,
                    image,
                    description,
                    status
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->prepare($sql);

        $stmt->bind_param(
            "iissddissi",
            $product->categoryId,
            $product->brandId,
            $product->proname,
            $product->slug,
            $product->price,
            $product->discountPrice,
            $product->quantity,
            $product->image,
            $product->description,
            $product->status
        );

        $stmt->execute();

        return $stmt->insert_id;
    }

    public function update(Product $product): bool
    {
        try {

            $sql = "UPDATE products
                    SET
                        category_id=?,
                        brand_id=?,
                        proname=?,
                        slug=?,
                        price=?,
                        discount_price=?,
                        quantity=?,
                        image=?,
                        description=?,
                        status=?
                    WHERE id=?";

            $stmt = $this->prepare($sql);

            $stmt->bind_param(
                "iissddissii",
                $product->categoryId,
                $product->brandId,
                $product->proname,
                $product->slug,
                $product->price,
                $product->discountPrice,
                $product->quantity,
                $product->image,
                $product->description,
                $product->status,
                $product->id
            );

            return $stmt->execute();

        } catch (Exception $e) {
            throw $e;
        }
    }


    public function delete(int $id): bool
{
    $sql = "UPDATE products SET status = 0 WHERE id = ?";

    $stmt = $this->prepare($sql);
    $stmt->bind_param("i", $id);

    return $stmt->execute();
}
    public function paging(int $page = 1, int $limit = 10): array
    {
        $list = [];

        $offset = ($page - 1) * $limit;

        $sql = "SELECT *
                FROM products
                ORDER BY id DESC
                LIMIT ? OFFSET ?";

        $stmt = $this->prepare($sql);

        $stmt->bind_param("ii", $limit, $offset);

        $stmt->execute();

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {

            $product = new Product(
                $row["category_id"],
                $row["brand_id"],
                $row["proname"],
                $row["slug"],
                $row["price"],
                $row["discount_price"],
                $row["quantity"],
                $row["image"],
                $row["description"],
                $row["status"]
            );

            $product->id = $row["id"];
            $product->createdAt = $row["created_at"];
            $product->updatedAt = $row["updated_at"];

            $list[] = $product;
        }

        return $list;
    }

    public function search(string $keyword): array
    {
        $list = [];

        try {

            $sql = "SELECT 
                        p.*,
                        c.catename AS catename,
                        b.brandname AS brandname
                    FROM products p
                    LEFT JOIN categories c
                        ON p.category_id = c.id
                    LEFT JOIN brands b
                        ON p.brand_id = b.id
                    WHERE p.proname LIKE ?
                    ORDER BY p.proname";

            $stmt = $this->prepare($sql);

            $search = "%{$keyword}%";

            $stmt->bind_param("s", $search);

            $stmt->execute();

            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {

                $product = new Product(
                    $row["category_id"],
                    $row["brand_id"],
                    $row["proname"],
                    $row["slug"],
                    $row["price"],
                    $row["discount_price"],
                    $row["quantity"],
                    $row["image"],
                    $row["description"],
                    $row["status"]
                );

                $product->id = $row["id"];
                $product->catename = $row["catename"] ?? "";
                $product->brandname = $row["brandname"] ?? "";
                $product->createdAt = $row["created_at"];
                $product->updatedAt = $row["updated_at"];

                $list[] = $product;
            }

        } catch (Exception $e) {
            throw $e;
        }

        return $list;
    }
    public function insertImage(int $productId, string $image): bool
    {
        $sql = "INSERT INTO product_images (product_id, image)
                VALUES (?, ?)";

        $stmt = $this->prepare($sql);

        $stmt->bind_param("is", $productId, $image);

        return $stmt->execute();
    }
    public function getImagesByProductId(int $productId): array
    {
        $list = [];

        $sql = "SELECT *
                FROM product_images
                WHERE product_id = ?
                ORDER BY id DESC";

        $stmt = $this->prepare($sql);

        $stmt->bind_param("i", $productId);

        $stmt->execute();

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $list[] = $row;
        }

        return $list;
    }
    public function deleteImage(int $id): bool
    {
        $sql = "SELECT image
                FROM product_images
                WHERE id = ?";

        $stmt = $this->prepare($sql);

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = $stmt->get_result();

        if (!$row = $result->fetch_assoc()) {
            return false;
        }

        $image = $row["image"];
        $file = __DIR__ . "/../uploads/products/" . $image;

        if (file_exists($file)) {
            unlink($file);
        }
        $sql = "DELETE FROM product_images WHERE id = ?";

        $stmt = $this->prepare($sql);

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
    public function updateStatus($id, $status)
    {
        $sql = "UPDATE products SET status = ? WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $status, $id);

        return $stmt->execute();
    }
    public function getPage(int $limit, int $offset)
    {

        $sql = "SELECT
                    p.*,
                    c.catename,
                    b.brandname
                FROM products p
                INNER JOIN categories c ON p.category_id = c.id
                INNER JOIN brands b ON p.brand_id = b.id
                ORDER BY p.proname
                LIMIT ? OFFSET ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = [];

        while ($row = $result->fetch_assoc()) {

            $product = new Product(
                $row["category_id"],
                $row["brand_id"],
                $row["proname"],
                $row["slug"],
                $row["price"],
                $row["discount_price"],
                $row["quantity"],
                $row["image"],
                $row["description"],
                $row["status"]
            );

            $product->id = $row["id"];
            $product->catename = $row["catename"];
            $product->brandname = $row["brandname"];
            $product->createdAt = $row["created_at"];
            $product->updatedAt = $row["updated_at"];

            $products[] = $product;
        }

        return $products;
    }
}