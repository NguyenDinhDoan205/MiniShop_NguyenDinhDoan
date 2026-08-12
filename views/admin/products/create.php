<?php

require_once "../../../dao/ProductDAO.php";
require_once "../../../dao/CategoryDAO.php";
require_once "../../../dao/BrandDAO.php";
require_once "../../../models/Product.php";

$pageTitle = "Thêm sản phẩm";

$productDAO = new ProductDAO();
$categoryDAO = new CategoryDAO();
$brandDAO = new BrandDAO();

$categories = $categoryDAO->getAll();
$brands = $brandDAO->getAll();

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $categoryId = (int)($_POST["category_id"] ?? 0);
    $brandId = (int)($_POST["brand_id"] ?? 0);

    $proname = trim($_POST["proname"] ?? "");

    $price = (float)($_POST["price"] ?? 0);
    $discountPrice = (float)($_POST["discount_price"] ?? 0);
    $quantity = (int)($_POST["quantity"] ?? 0);
    $description = trim($_POST["description"] ?? "");
    $status = (int)($_POST["status"] ?? 1);

   $slug = strtolower(trim($proname));

    $slug = preg_replace('/[^a-zA-Z0-9]+/', '-', $slug);

    $slug = trim($slug, '-');

    $slug .= "-" . time();

    if ($proname == "") {

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

    $image = "";

    if ($error == "") {

        if (isset($_FILES["image"]) &&
            $_FILES["image"]["error"] == 0) {

            $uploadDir = "../../../uploads/";

            $imageName = time() . "_" .
                basename($_FILES["image"]["name"]);

            $target = $uploadDir . $imageName;

            if (move_uploaded_file(
                $_FILES["image"]["tmp_name"],
                $target
            )) {

                $image = $imageName;
            }
        }
    }
    if ($error == "") {

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
    $productId = $productDAO->insert($product);

    if ($productId > 0) {

        if (isset($_FILES["images"])) {

            $uploadDir = "../../../uploads/";

            foreach ($_FILES["images"]["name"] as $key => $name) {

                if ($_FILES["images"]["error"][$key] != 0) {
                    continue;
                }

                $imageName = time() . "_" . $key . "_" . basename($name);

                $target = $uploadDir . $imageName;

                if (move_uploaded_file(
                    $_FILES["images"]["tmp_name"][$key],
                    $target
                )) {

                    $productDAO->insertImage(
                        $productId,
                        $imageName
                    );
                }
            }
        }

        header("Location: index.php?success=1");
        exit;

    } else {

        $error = "Không thể thêm sản phẩm.";
    }
    }
}


ob_start();

?>

<h2 class="mb-4">
    Thêm sản phẩm
</h2>

<div class="card">

    <div class="card-header bg-primary text-white">
        Thông tin sản phẩm
    </div>

    <div class="card-body">

        <?php if (!empty($error)): ?>

            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>


        <form
            method="post"
            enctype="multipart/form-data">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Danh mục
                    </label>

                    <select
                        name="category_id"
                        class="form-select"
                        required>

                        <option value="">
                            -- Chọn danh mục --
                        </option>

                        <?php foreach ($categories as $category): ?>

                            <option
                                value="<?= $category->id ?>"
                                <?= (
                                    isset($_POST["category_id"]) &&
                                    $_POST["category_id"] == $category->id
                                ) ? "selected" : "" ?>>

                                <?= htmlspecialchars($category->catename) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Thương hiệu
                    </label>

                    <select
                        name="brand_id"
                        class="form-select"
                        required>

                        <option value="">
                            -- Chọn thương hiệu --
                        </option>

                        <?php foreach ($brands as $brand): ?>

                            <option
                                value="<?= $brand->id ?>"
                                <?= (
                                    isset($_POST["brand_id"]) &&
                                    $_POST["brand_id"] == $brand->id
                                ) ? "selected" : "" ?>>

                                <?= htmlspecialchars($brand->brandname) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>
                <div class="col-md-12 mb-3">

                    <label class="form-label">
                        Tên sản phẩm
                    </label>

                    <input
                        type="text"
                        name="proname"
                        class="form-control"
                        value="<?= htmlspecialchars($_POST["proname"] ?? "") ?>"
                        required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Giá
                    </label>

                    <input
                        type="number"
                        name="price"
                        class="form-control"
                        min="0"
                        step="1000"
                        value="<?= htmlspecialchars($_POST["price"] ?? "") ?>"
                        required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Giá khuyến mãi
                    </label>

                    <input
                        type="number"
                        name="discount_price"
                        class="form-control"
                        min="0"
                        step="1000"
                        value="<?= htmlspecialchars($_POST["discount_price"] ?? "0") ?>">

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Số lượng
                    </label>

                    <input
                        type="number"
                        name="quantity"
                        class="form-control"
                        min="0"
                        value="<?= htmlspecialchars($_POST["quantity"] ?? "0") ?>">

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Trạng thái
                    </label>

                    <select
                        name="status"
                        class="form-select">

                        <option value="1">
                            Hiển thị
                        </option>

                        <option value="0">
                            Ẩn
                        </option>

                    </select>

                </div>
                <div class="col-md-12 mb-3">

                    <label class="form-label">
                        Hình ảnh chính
                    </label>

                    <input
                        type="file"
                        id="mainImage"
                        name="image"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.gif,.webp">

                </div>

                <div class="col-md-12 mb-3">

                    <div
                        class="border rounded d-flex justify-content-center align-items-center"
                        style="width:220px; height:230px;">

                        <img
                            id="mainPreview"
                            src=""
                            style="
                                display:none;
                                max-width:100%;
                                max-height:100%;
                                object-fit:cover;
                            ">

                        <span
                            id="mainNoImage"
                            class="text-secondary">

                            Chưa chọn hình ảnh

                        </span>

                    </div>

                </div>

                <div class="col-md-12 mb-3">

                    <label class="form-label">
                        Hình ảnh phụ
                    </label>

                    <input
                        type="file"
                        id="galleryImages"
                        name="images[]"
                        class="form-control"
                        multiple
                        accept=".jpg,.jpeg,.png,.gif,.webp">

                    <small class="text-muted">
                        Có thể chọn nhiều hình ảnh.
                    </small>

                </div>

                <div class="col-md-12 mb-3">

                    <div
                        id="galleryPreview"
                        class="d-flex flex-wrap gap-2">

                    </div>

                </div>

                <div class="col-md-12 mb-3">

                    <label class="form-label">
                        Mô tả
                    </label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="5"><?= htmlspecialchars($_POST["description"] ?? "") ?></textarea>

                </div>

            </div>


            <div class="text-end">

                <button
                    type="submit"
                    class="btn btn-success">

                    <i class="bi bi-save"></i>
                    Lưu sản phẩm

                </button>

                <a
                    href="index.php"
                    class="btn btn-secondary">

                    <i class="bi bi-arrow-left"></i>
                    Quay lại

                </a>

            </div>

        </form>

    </div>

</div>


<script>
document
    .getElementById("mainImage")
    .addEventListener("change", function () {

        const file = this.files[0];

        const preview =
            document.getElementById("mainPreview");

        const noImage =
            document.getElementById("mainNoImage");

        if (file) {

            const reader =
                new FileReader();

            reader.onload = function (e) {

                preview.src =
                    e.target.result;

                preview.style.display =
                    "block";

                noImage.style.display =
                    "none";
            };

            reader.readAsDataURL(file);

        } else {

            preview.style.display =
                "none";

            noImage.style.display =
                "block";
        }

    });

document
    .getElementById("galleryImages")
    .addEventListener("change", function () {

        const preview =
            document.getElementById("galleryPreview");

        preview.innerHTML = "";

        const files = this.files;

        for (let i = 0; i < files.length; i++) {

            const file = files[i];

            const reader =
                new FileReader();

            reader.onload = function (e) {

                const img =
                    document.createElement("img");

                img.src =
                    e.target.result;

                img.width = 120;

                img.height = 120;

                img.style.objectFit =
                    "cover";

                img.className =
                    "img-thumbnail";

                preview.appendChild(img);
            };

            reader.readAsDataURL(file);
        }

    });

</script>


<?php

$content = ob_get_clean();

include "../layouts/master.php";
?>
