<?php

ob_start();

?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h2>Chỉnh sửa sản phẩm</h2>

        <a
            href="/MiniShop_NguyenDinhDoan/index.php?controller=product&action=index"
            class="btn btn-secondary">

            <i class="bi bi-arrow-left"></i>
            Quay lại

        </a>

    </div>


    <?php if (!empty($errors["general"])): ?>

        <div class="alert alert-danger">
            <?= htmlspecialchars($errors["general"]) ?>
        </div>

    <?php endif; ?>


    <div class="card">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">
                Cập nhật sản phẩm #<?= (int)$product->id ?>
            </h5>

        </div>


        <div class="card-body">

            <form
                method="POST"
                action="/MiniShop_NguyenDinhDoan/index.php?controller=product&action=edit&id=<?= (int)$product->id ?>"
                enctype="multipart/form-data">

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($_SESSION["csrf_token"] ?? "") ?>">

                <div class="mb-3">

                    <label class="form-label">
                        Danh mục
                    </label>

                    <select
                        name="categoryId"
                        class="form-select">

                        <option value="">
                            -- Chọn danh mục --
                        </option>

                        <?php foreach ($categories as $category): ?>

                            <option
                                value="<?= (int)$category->id ?>"
                                <?= (int)$category->id == (int)$product->categoryId ? "selected" : "" ?>>

                                <?= htmlspecialchars($category->catename) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <?php if (!empty($errors["categoryId"])): ?>

                        <div class="text-danger mt-1">
                            <?= htmlspecialchars($errors["categoryId"]) ?>
                        </div>

                    <?php endif; ?>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Thương hiệu
                    </label>

                    <select
                        name="brandId"
                        class="form-select">

                        <option value="">
                            -- Chọn thương hiệu --
                        </option>

                        <?php foreach ($brands as $brand): ?>

                            <option
                                value="<?= (int)$brand->id ?>"
                                <?= (int)$brand->id == (int)$product->brandId ? "selected" : "" ?>>

                                <?= htmlspecialchars($brand->brandname) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <?php if (!empty($errors["brandId"])): ?>

                        <div class="text-danger mt-1">
                            <?= htmlspecialchars($errors["brandId"]) ?>
                        </div>

                    <?php endif; ?>

                </div>
                <div class="mb-3">

                    <label class="form-label">
                        Tên sản phẩm
                    </label>

                    <input
                        type="text"
                        name="proname"
                        class="form-control"
                        value="<?= htmlspecialchars($product->proname ?? "") ?>">

                    <?php if (!empty($errors["proname"])): ?>

                        <div class="text-danger mt-1">
                            <?= htmlspecialchars($errors["proname"]) ?>
                        </div>

                    <?php endif; ?>

                </div>
                <div class="mb-3">

                    <label class="form-label">
                        Slug
                    </label>

                    <input
                        type="text"
                        name="slug"
                        class="form-control"
                        value="<?= htmlspecialchars($product->slug ?? "") ?>">

                    <?php if (!empty($errors["slug"])): ?>

                        <div class="text-danger mt-1">
                            <?= htmlspecialchars($errors["slug"]) ?>
                        </div>

                    <?php endif; ?>

                </div>

                <div class="row">

                    <div class="col-md-6">

                        <div class="mb-3">

                            <label class="form-label">
                                Giá
                            </label>

                            <input
                                type="number"
                                name="price"
                                class="form-control"
                                value="<?= htmlspecialchars($product->price ?? 0) ?>">

                            <?php if (!empty($errors["price"])): ?>

                                <div class="text-danger mt-1">
                                    <?= htmlspecialchars($errors["price"]) ?>
                                </div>

                            <?php endif; ?>

                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="mb-3">

                            <label class="form-label">
                                Giá khuyến mãi
                            </label>

                            <input
                                type="number"
                                name="discountPrice"
                                class="form-control"
                                value="<?= htmlspecialchars($product->discountPrice ?? 0) ?>">

                            <?php if (!empty($errors["discountPrice"])): ?>

                                <div class="text-danger mt-1">
                                    <?= htmlspecialchars($errors["discountPrice"]) ?>
                                </div>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>
                <div class="mb-3">

                    <label class="form-label">
                        Số lượng
                    </label>

                    <input
                        type="number"
                        name="quantity"
                        class="form-control"
                        value="<?= htmlspecialchars($product->quantity ?? 0) ?>">

                    <?php if (!empty($errors["quantity"])): ?>

                        <div class="text-danger mt-1">
                            <?= htmlspecialchars($errors["quantity"]) ?>
                        </div>

                    <?php endif; ?>

                </div>
                <div class="mb-4">

                    <label class="form-label">
                        <strong>Ảnh sản phẩm</strong>
                    </label>

                    <div class="mb-3">

                        <?php if (!empty($product->image)): ?>

                            <img
                                id="mainImagePreview"
                                src="/MiniShop_NguyenDinhDoan/uploads/<?= htmlspecialchars($product->image) ?>"
                                width="180"
                                height="180"
                                class="img-thumbnail"
                                style="object-fit: cover;">

                        <?php else: ?>

                            <img
                                id="mainImagePreview"
                                src=""
                                width="180"
                                height="180"
                                class="img-thumbnail"
                                style="display:none; object-fit:cover;">

                            <p
                                id="noMainImage"
                                class="text-muted">

                                Chưa có ảnh

                            </p>

                        <?php endif; ?>

                    </div>


                    <input
                        type="file"
                        name="image"
                        id="mainImageInput"
                        class="form-control"
                        accept="image/jpeg,image/png,image/webp,image/gif">

                    <small class="text-muted">

                        JPG, PNG, WEBP, GIF - tối đa 5MB

                    </small>


                    <?php if (!empty($errors["image"])): ?>

                        <div class="text-danger mt-1">
                            <?= htmlspecialchars($errors["image"]) ?>
                        </div>

                    <?php endif; ?>

                </div>

                <div class="mb-4">

                    <label class="form-label">

                        <strong>Thêm hình ảnh phụ</strong>

                    </label>

                    <input
                        type="file"
                        name="images[]"
                        id="imagesInput"
                        class="form-control"
                        multiple
                        accept="image/jpeg,image/png,image/webp,image/gif">

                    <small class="text-muted">

                        Có thể chọn nhiều hình ảnh.

                    </small>

                </div>


                <div
                    id="newImagesPreview"
                    class="d-flex flex-wrap gap-3 mb-4">
                </div>

                <div class="mb-4">

                    <label class="form-label">

                        <strong>Hình ảnh phụ hiện tại</strong>

                    </label>


                    <div class="d-flex flex-wrap gap-3">

                        <?php if (!empty($gallery)): ?>

                            <?php foreach ($gallery as $item): ?>

                                <div
                                    class="text-center border rounded p-2"
                                    style="width:150px;">

                                    <img
                                        src="/MiniShop_NguyenDinhDoan/uploads/<?= htmlspecialchars($item["image"] ?? "") ?>"
                                        width="120"
                                        height="120"
                                        class="img-thumbnail mb-2"
                                        style="object-fit:cover;">


                                    <form
                                        method="POST"
                                        action="/MiniShop_NguyenDinhDoan/index.php?controller=product&action=deleteImage"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa ảnh này không?');">

                                        <input
                                            type="hidden"
                                            name="image_id"
                                            value="<?= (int)$item["id"] ?>">

                                        <input
                                            type="hidden"
                                            name="csrf_token"
                                            value="<?= htmlspecialchars($_SESSION["csrf_token"] ?? "") ?>">

                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm">

                                            <i class="bi bi-trash"></i>
                                            Xóa

                                        </button>

                                    </form>

                                </div>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <span class="text-muted">

                                Chưa có hình ảnh phụ

                            </span>

                        <?php endif; ?>

                    </div>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Mô tả
                    </label>

                    <textarea
                        name="description"
                        rows="5"
                        class="form-control"><?= htmlspecialchars($product->description ?? "") ?></textarea>

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Trạng thái
                    </label>


                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="radio"
                            name="status"
                            value="1"
                            <?= (int)$product->status === 1 ? "checked" : "" ?>>

                        <label class="form-check-label">

                            Hiển thị

                        </label>

                    </div>


                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="radio"
                            name="status"
                            value="0"
                            <?= (int)$product->status === 0 ? "checked" : "" ?>>

                        <label class="form-check-label">

                            Ẩn

                        </label>

                    </div>

                </div>
                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="bi bi-save"></i>
                    Cập nhật

                </button>


                <a
                    href="/MiniShop_NguyenDinhDoan/index.php?controller=product&action=index"
                    class="btn btn-secondary">

                    <i class="bi bi-arrow-left"></i>
                    Quay lại

                </a>

            </form>

        </div>

    </div>

</div>


<script>

document
    .getElementById("mainImageInput")
    .addEventListener("change", function () {

        const file = this.files[0];

        if (!file) {
            return;
        }

        if (!file.type.startsWith("image/")) {

            alert("Vui lòng chọn file hình ảnh.");

            this.value = "";

            return;
        }

        if (file.size > 5 * 1024 * 1024) {

            alert("Ảnh không được vượt quá 5MB.");

            this.value = "";

            return;
        }

        const preview =
            document.getElementById("mainImagePreview");

        const noImage =
            document.getElementById("noMainImage");

        preview.src =
            URL.createObjectURL(file);

        preview.style.display = "block";

        if (noImage) {
            noImage.style.display = "none";
        }

    });


document
    .getElementById("imagesInput")
    .addEventListener("change", function () {

        const preview =
            document.getElementById("newImagesPreview");

        preview.innerHTML = "";

        const files = this.files;

        for (let i = 0; i < files.length; i++) {

            const file = files[i];

            if (!file.type.startsWith("image/")) {
                continue;
            }

            const reader = new FileReader();

            reader.onload = function (e) {

                const div =
                    document.createElement("div");

                div.style.width = "120px";

                div.innerHTML = `
                    <img
                        src="${e.target.result}"
                        width="120"
                        height="120"
                        class="img-thumbnail"
                        style="object-fit:cover;">
                `;

                preview.appendChild(div);

            };

            reader.readAsDataURL(file);
        }

    });

</script>


<?php

$content = ob_get_clean();

include __DIR__ . "/../layouts/master.php";

?>