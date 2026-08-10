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

    $categoryId = (int)$_POST["category_id"];
    $brandId = (int)$_POST["brand_id"];
    $proname = trim($_POST["proname"]);
    $price = (float)$_POST["price"];
    $discountPrice = (float)$_POST["discount_price"];
    $quantity = (int)$_POST["quantity"];
    $description = trim($_POST["description"]);
    $status = (int)$_POST["status"];
    $slug = strtolower(trim($proname));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    $slug = trim($slug, '-');

    $image = "";

     if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {

        $image = time() . "_" . $_FILES["image"]["name"];

        move_uploaded_file(
            $_FILES["image"]["tmp_name"],
            "../../../uploads/products" . $image
        );
    }


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

        if ($productDAO->insert($product)) {

            header("Location: index.php?success=1");
            exit;

        } else {

            $error = "Không thể thêm sản phẩm.";

        }
    }
}

ob_start();
?>
<h2 class="mb-4">Thêm sản phẩm</h2>

       

        <div class="card-body">

            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)) : ?>
                <div class="alert alert-success">
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <form method="post"
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
                               Chọn danh mục
                            </option>

                            <?php foreach ($categories as $category) : ?>

                                <option value="<?= $category->id ?>">
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
                                Chọn thương hiệu
                            </option>

                            <?php foreach ($brands as $brand) : ?>

                                <option value="<?= $brand->id ?>">
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
                            value="0">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Số lượng
                        </label>

                        <input
                            type="number"
                            name="quantity"
                            class="form-control"
                            value="0"
                            min="0">

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
                        Hình ảnh
                    </label>

                    <input
                        type="file"
                        id="image"
                        name="image"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.gif,.webp">

                </div>

              <div class="col-md-12 mb-3">
                  <div class="border rounded d-flex justify-content-center align-items-center"
                    style="width:220px; height:230px;">

                    <img
                        id="preview"
                        src=""
                        style="display:none; max-width:100%; max-height:100%;">

                    <span id="noImage" class="text-secondary">
                        Chưa chọn hình ảnh
                    </span>

                </div>

            </div>

                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Mô tả
                        </label>

                        <textarea
                            name="description"
                            class="form-control"
                            rows="5">
                        </textarea>

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

                        Quay lại

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>