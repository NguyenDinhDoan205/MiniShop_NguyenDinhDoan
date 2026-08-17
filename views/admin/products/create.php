<?php
if (!defined('APP_ENTRY')) {
    header("Location: /MiniShop_NguyenDinhDoan/admin/login");
    exit;
}

$pageTitle  = $pageTitle ?? "Thêm sản phẩm";
$categories = $categories ?? [];
$brands     = $brands ?? [];
$error      = $error ?? "";
$postData   = $postData ?? [];

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
            action="/MiniShop_NguyenDinhDoan/admin/product/create"
            method="POST"
            enctype="multipart/form-data">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars($_SESSION["csrf_token"] ?? "") ?>">

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
                                    isset($postData["category_id"]) &&
                                    $postData["category_id"] == $category->id
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
                                    isset($postData["brand_id"]) &&
                                    $postData["brand_id"] == $brand->id
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
                        value="<?= htmlspecialchars($postData["proname"] ?? "") ?>"
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
                        value="<?= htmlspecialchars($postData["price"] ?? "") ?>"
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
                        value="<?= htmlspecialchars($postData["discount_price"] ?? "0") ?>">

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
                        value="<?= htmlspecialchars($postData["quantity"] ?? "0") ?>">

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
                        rows="5"><?= htmlspecialchars($postData["description"] ?? "") ?></textarea>

                </div>

            </div>

            <div class="text-end">

                <button
                    type="submit"
                    class="btn btn-success">

                    <i class="bi bi-save"></i>
                    Lưu sản phẩm

                </button>

                
                    href="/MiniShop_NguyenDinhDoan/admin/product"
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
        .addEventListener("change", function() {

            const file = this.files[0];
            const preview = document.getElementById("mainPreview");
            const noImage = document.getElementById("mainNoImage");

            if (file) {

                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = "block";
                    noImage.style.display = "none";
                };

                reader.readAsDataURL(file);

            } else {

                preview.style.display = "none";
                noImage.style.display = "block";
            }

        });

    document
        .getElementById("galleryImages")
        .addEventListener("change", function() {

            const preview = document.getElementById("galleryPreview");
            preview.innerHTML = "";

            const files = this.files;

            for (let i = 0; i < files.length; i++) {

                const file = files[i];
                const reader = new FileReader();

                reader.onload = function(e) {
                    const img = document.createElement("img");
                    img.src = e.target.result;
                    img.width = 120;
                    img.height = 120;
                    img.style.objectFit = "cover";
                    img.className = "img-thumbnail";
                    preview.appendChild(img);
                };

                reader.readAsDataURL(file);
            }

        });
</script>

<?php

$content = ob_get_clean();

require __DIR__ . "/../layouts/master.php";