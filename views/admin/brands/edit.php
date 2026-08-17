<?php

$pageTitle = "Cập nhật sản phẩm";

ob_start();

?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>
            Cập nhật sản phẩm
        </h2>

        <a
            href="index.php?controller=product&action=index"
            class="btn btn-secondary"
        >
            <i class="bi bi-arrow-left"></i>
            Quay lại
        </a>

    </div>


    <?php if (!empty($errors["general"])): ?>

        <div class="alert alert-danger">
            <?= htmlspecialchars($errors["general"]) ?>
        </div>

    <?php endif; ?>


    <form
        action="index.php?controller=product&action=edit&id=<?= (int)$product->id ?>"
        method="POST"
        enctype="multipart/form-data"
    >

        <!-- CSRF -->

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($_SESSION["csrf_token"] ?? "") ?>"
        >


        <div class="row">

            <!-- CỘT TRÁI -->

            <div class="col-md-8">

                <div class="card mb-4">

                    <div class="card-header">
                        <strong>Thông tin sản phẩm</strong>
                    </div>

                    <div class="card-body">

                        <!-- Tên -->

                        <div class="mb-3">

                            <label class="form-label">
                                Tên sản phẩm
                            </label>

                            <input
                                type="text"
                                name="proname"
                                class="form-control"
                                value="<?= htmlspecialchars($product->proname ?? "") ?>"
                            >

                            <?php if (!empty($errors["proname"])): ?>

                                <small class="text-danger">
                                    <?= htmlspecialchars($errors["proname"]) ?>
                                </small>

                            <?php endif; ?>

                        </div>


                        <!-- Slug -->

                        <div class="mb-3">

                            <label class="form-label">
                                Slug
                            </label>

                            <input
                                type="text"
                                name="slug"
                                class="form-control"
                                value="<?= htmlspecialchars($product->slug ?? "") ?>"
                            >

                            <?php if (!empty($errors["slug"])): ?>

                                <small class="text-danger">
                                    <?= htmlspecialchars($errors["slug"]) ?>
                                </small>

                            <?php endif; ?>

                        </div>


                        <!-- Danh mục -->

                        <div class="mb-3">

                            <label class="form-label">
                                Danh mục
                            </label>

                            <select
                                name="categoryId"
                                class="form-select"
                            >

                                <option value="">
                                    -- Chọn danh mục --
                                </option>

                                <?php foreach ($categories as $category): ?>

                                    <option
                                        value="<?= (int)$category->id ?>"
                                        <?= (
                                            (int)$product->categoryId
                                            ===
                                            (int)$category->id
                                        )
                                            ? "selected"
                                            : ""
                                        ?>
                                    >
                                        <?= htmlspecialchars(
                                            $category->categoryname
                                            ?? $category->name
                                            ?? ""
                                        ) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                            <?php if (!empty($errors["categoryId"])): ?>

                                <small class="text-danger">
                                    <?= htmlspecialchars(
                                        $errors["categoryId"]
                                    ) ?>
                                </small>

                            <?php endif; ?>

                        </div>


                        <!-- Thương hiệu -->

                        <div class="mb-3">

                            <label class="form-label">
                                Thương hiệu
                            </label>

                            <select
                                name="brandId"
                                class="form-select"
                            >

                                <option value="">
                                    -- Chọn thương hiệu --
                                </option>

                                <?php foreach ($brands as $brand): ?>

                                    <option
                                        value="<?= (int)$brand->id ?>"
                                        <?= (
                                            (int)$product->brandId
                                            ===
                                            (int)$brand->id
                                        )
                                            ? "selected"
                                            : ""
                                        ?>
                                    >
                                        <?= htmlspecialchars(
                                            $brand->brandname
                                            ?? ""
                                        ) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                            <?php if (!empty($errors["brandId"])): ?>

                                <small class="text-danger">
                                    <?= htmlspecialchars(
                                        $errors["brandId"]
                                    ) ?>
                                </small>

                            <?php endif; ?>

                        </div>


                        <!-- Giá -->

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
                                        value="<?= htmlspecialchars(
                                            $product->price ?? 0
                                        ) ?>"
                                    >

                                    <?php if (!empty($errors["price"])): ?>

                                        <small class="text-danger">
                                            <?= htmlspecialchars(
                                                $errors["price"]
                                            ) ?>
                                        </small>

                                    <?php endif; ?>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Giá giảm
                                    </label>

                                    <input
                                        type="number"
                                        name="discountPrice"
                                        class="form-control"
                                        value="<?= htmlspecialchars(
                                            $product->discountPrice ?? 0
                                        ) ?>"
                                    >

                                </div>

                            </div>

                        </div>


                        <!-- Số lượng -->

                        <div class="mb-3">

                            <label class="form-label">
                                Số lượng
                            </label>

                            <input
                                type="number"
                                name="quantity"
                                class="form-control"
                                value="<?= htmlspecialchars(
                                    $product->quantity ?? 0
                                ) ?>"
                            >

                            <?php if (!empty($errors["quantity"])): ?>

                                <small class="text-danger">
                                    <?= htmlspecialchars(
                                        $errors["quantity"]
                                    ) ?>
                                </small>

                            <?php endif; ?>

                        </div>


                        <!-- Mô tả -->

                        <div class="mb-3">

                            <label class="form-label">
                                Mô tả
                            </label>

                            <textarea
                                name="description"
                                rows="5"
                                class="form-control"
                            ><?= htmlspecialchars(
                                $product->description ?? ""
                            ) ?></textarea>

                        </div>


                        <!-- Trạng thái -->

                        <div class="mb-3">

                            <label class="form-label">
                                Trạng thái
                            </label>

                            <div>

                                <div class="form-check">

                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="status"
                                        value="1"
                                        <?= (
                                            (int)$product->status === 1
                                        )
                                            ? "checked"
                                            : ""
                                        ?>
                                    >

                                    <label class="form-check-label">
                                        Hoạt động
                                    </label>

                                </div>


                                <div class="form-check">

                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="status"
                                        value="0"
                                        <?= (
                                            (int)$product->status === 0
                                        )
                                            ? "checked"
                                            : ""
                                        ?>
                                    >

                                    <label class="form-check-label">
                                        Ngừng hoạt động
                                    </label>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- CỘT PHẢI -->

            <div class="col-md-4">

                <div class="card mb-4">

                    <div class="card-header">

                        <strong>
                            Hình ảnh sản phẩm
                        </strong>

                    </div>

                    <div class="card-body">


                        <!-- ẢNH HIỆN TẠI -->

                        <div class="text-center mb-3">

                            <?php if (!empty($product->image)): ?>

                                <img
                                    id="previewImage"
                                    src="../../../uploads/products/<?= htmlspecialchars(
                                        $product->image
                                    ) ?>"
                                    class="img-thumbnail"
                                    style="
                                        width: 220px;
                                        height: 220px;
                                        object-fit: cover;
                                    "
                                >

                            <?php else: ?>

                                <img
                                    id="previewImage"
                                    src=""
                                    class="img-thumbnail"
                                    style="
                                        width: 220px;
                                        height: 220px;
                                        object-fit: cover;
                                        display: none;
                                    "
                                >

                                <div
                                    id="noImage"
                                    class="text-muted"
                                >
                                    Chưa có hình ảnh
                                </div>

                            <?php endif; ?>

                        </div>


                        <!-- CHỌN ẢNH -->

                        <div class="mb-3">

                            <label class="form-label">
                                Chọn hình ảnh mới
                            </label>

                            <input
                                type="file"
                                name="image"
                                id="imageInput"
                                class="form-control"
                                accept="image/jpeg,image/png,image/webp,image/gif"
                            >

                            <small class="text-muted">
                                JPG, PNG, WEBP, GIF - tối đa 5MB
                            </small>

                            <?php if (!empty($errors["image"])): ?>

                                <small class="text-danger d-block">
                                    <?= htmlspecialchars(
                                        $errors["image"]
                                    ) ?>
                                </small>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>


                <!-- GALLERY -->

                <?php if (!empty($gallery)): ?>

                    <div class="card mb-4">

                        <div class="card-header">

                            <strong>
                                Hình ảnh khác
                            </strong>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <?php foreach ($gallery as $image): ?>

                                    <div class="col-6 mb-3">

                                        <img
                                            src="../../../uploads/products/<?= htmlspecialchars(
                                                $image->image
                                                ?? ""
                                            ) ?>"
                                            class="img-thumbnail"
                                            style="
                                                width: 100%;
                                                height: 120px;
                                                object-fit: cover;
                                            "
                                        >

                                    </div>

                                <?php endforeach; ?>

                            </div>

                        </div>

                    </div>

                <?php endif; ?>

            </div>

        </div>


        <!-- BUTTON -->

        <div class="mt-3 mb-5">

            <button
                type="submit"
                class="btn btn-primary"
            >

                <i class="bi bi-save"></i>

                Cập nhật sản phẩm

            </button>


            <a
                href="index.php?controller=product&action=index"
                class="btn btn-secondary"
            >

                Quay lại

            </a>

        </div>

    </form>

</div>


<!-- PREVIEW ẢNH -->

<script>

document
    .getElementById("imageInput")
    ?.addEventListener(
        "change",
        function (event) {

            const file =
                event.target.files[0];

            if (!file) {
                return;
            }

            /*
            | Kiểm tra loại file
            */

            if (
                !file.type.startsWith("image/")
            ) {

                alert(
                    "Vui lòng chọn file hình ảnh."
                );

                event.target.value = "";

                return;
            }

            /*
            | Kiểm tra dung lượng
            */

            if (
                file.size > 5 * 1024 * 1024
            ) {

                alert(
                    "Hình ảnh không được vượt quá 5MB."
                );

                event.target.value = "";

                return;
            }

            /*
            | Hiển thị preview
            */

            const preview =
                document.getElementById(
                    "previewImage"
                );

            const noImage =
                document.getElementById(
                    "noImage"
                );

            if (preview) {

                preview.src =
                    URL.createObjectURL(file);

                preview.style.display =
                    "block";
            }

            if (noImage) {

                noImage.style.display =
                    "none";
            }
        }
    );

</script>


<?php

$content = ob_get_clean();

include __DIR__ . "/../layouts/master.php";