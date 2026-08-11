<?php
require_once "../../../dao/ProductDAO.php";
require_once "../../../dao/CategoryDAO.php";
require_once "../../../dao/BrandDAO.php";

$productDAO = new ProductDAO();
$categoryDAO = new CategoryDAO();
$brandDAO = new BrandDAO();

$pageTitle = "Cập nhật sản phẩm";

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

$product = $productDAO->findById($id);

if ($product == null) {
    die("Không tìm thấy sản phẩm.");
}

$gallery = $productDAO->getImagesByProductId($product->id);

$categories = $categoryDAO->getAll();
$brands = $brandDAO->getAll();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

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

    if ($product->proname == "") {
        $errors["proname"] = "Tên sản phẩm không được để trống.";
    }

    if ($product->slug == "") {
        $errors["slug"] = "Slug không được để trống.";
    }

    if ($product->price <= 0) {
        $errors["price"] = "Giá phải lớn hơn 0.";
    }

    if ($product->discountPrice < 0) {
        $errors["discountPrice"] = "Giá khuyến mãi không hợp lệ.";
    }

    if ($product->quantity < 0) {
        $errors["quantity"] = "Số lượng không hợp lệ.";
    }
    if (
        isset($_FILES["image"]) &&
        $_FILES["image"]["error"] == UPLOAD_ERR_OK
    ) {

        $extension = strtolower(
            pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION)
        );

        $allow = ["jpg", "jpeg", "png", "gif", "webp"];

        if (!in_array($extension, $allow)) {

            $errors["image"] = "Ảnh không đúng định dạng.";

        } else {

            $filename = time() . "_" . basename($_FILES["image"]["name"]);

            $uploadDir = "../../../uploads/";

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $uploadFile = $uploadDir . $filename;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $uploadFile)) {

                if (
                    !empty($product->image) &&
                    file_exists($uploadDir . $product->image)
                ) {
                    unlink($uploadDir . $product->image);
                }

                $product->image = $filename;

            } else {

                $errors["image"] = "Không thể tải ảnh lên.";

            }
        }
    }if (count($errors) == 0) {

    if ($productDAO->update($product)) {

        if (isset($_FILES["images"])) {

            $uploadDir = "../../../uploads/products/";

            foreach ($_FILES["images"]["name"] as $key => $name) {

                if ($_FILES["images"]["error"][$key] != UPLOAD_ERR_OK) {
                    continue;
                }

                $extension = strtolower(
                    pathinfo($name, PATHINFO_EXTENSION)
                );

                $allow = ["jpg", "jpeg", "png", "gif", "webp"];

                if (!in_array($extension, $allow)) {
                    continue;
                }

                $imageName = time() . "_" . $key . "_" . basename($name);

                $uploadFile = $uploadDir . $imageName;

                if (move_uploaded_file(
                    $_FILES["images"]["tmp_name"][$key],
                    $uploadFile
                )) {

                    $productDAO->insertImage(
                        $product->id,
                        $imageName
                    );
                }
            }
        }

        header("Location: index.php");
        exit;

    } else {

        $errors["general"] = "Cập nhật thất bại.";
    }
}


}




ob_start();
?>
<div class="container mt-4">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="bi bi-pencil-square"></i>
                Cập nhật sản phẩm
            </h4>
        </div>

        <div class="card-body">

            <?php if(isset($errors["general"])): ?>

                <div class="alert alert-danger">
                    <?= $errors["general"] ?>
                </div>

            <?php endif; ?>

            <form method="post" enctype="multipart/form-data">

                <div class="mb-3">

                    <label class="form-label">Danh mục</label>

                    <select name="categoryId" class="form-select">

                        <option value="">-- Chọn danh mục --</option>

                        <?php foreach($categories as $cate): ?>

                            <option
                                value="<?= $cate->id ?>"
                                <?= $cate->id == $product->categoryId ? "selected" : "" ?>>

                                <?= htmlspecialchars($cate->catename) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <small class="text-danger">
                        <?= $errors["categoryId"] ?? "" ?>
                    </small>

                </div>

                <div class="mb-3">

                    <label class="form-label">Thương hiệu</label>

                    <select name="brandId" class="form-select">

                        <option value="">-- Chọn thương hiệu --</option>

                        <?php foreach($brands as $brand): ?>

                            <option
                                value="<?= $brand->id ?>"
                                <?= $brand->id == $product->brandId ? "selected" : "" ?>>

                                <?= htmlspecialchars($brand->brandname) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <small class="text-danger">
                        <?= $errors["brandId"] ?? "" ?>
                    </small>

                </div>

                <div class="mb-3">

                    <label class="form-label">Tên sản phẩm</label>

                    <input
                        type="text"
                        name="proname"
                        class="form-control"
                        value="<?= htmlspecialchars($product->proname) ?>">

                    <small class="text-danger">
                        <?= $errors["proname"] ?? "" ?>
                    </small>

                </div>

                <div class="mb-3">

                    <label class="form-label">Slug</label>

                    <input
                        type="text"
                        name="slug"
                        class="form-control"
                        value="<?= htmlspecialchars($product->slug) ?>">

                    <small class="text-danger">
                        <?= $errors["slug"] ?? "" ?>
                    </small>

                </div>

                <div class="row">

                    <div class="col-md-6">

                        <div class="mb-3">

                            <label class="form-label">Giá</label>

                            <input
                                type="number"
                                name="price"
                                class="form-control"
                                value="<?= $product->price ?>">

                            <small class="text-danger">
                                <?= $errors["price"] ?? "" ?>
                            </small>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="mb-3">

                            <label class="form-label">Giá khuyến mãi</label>

                            <input
                                type="number"
                                name="discountPrice"
                                class="form-control"
                                value="<?= $product->discountPrice ?>">

                            <small class="text-danger">
                                <?= $errors["discountPrice"] ?? "" ?>
                            </small>

                        </div>

                    </div>

                </div>

                <div class="mb-3">

                    <label class="form-label">Số lượng</label>

                    <input
                        type="number"
                        name="quantity"
                        class="form-control"
                        value="<?= $product->quantity ?>">

                    <small class="text-danger">
                        <?= $errors["quantity"] ?? "" ?>
                    </small>

                </div>

                 <div class="mb-3">

        <label class="form-label">Hình ảnh hiện tại</label>
        <br>

        <?php if ($product->image != ""): ?>

            <img
                src="../../../uploads/<?= $product->image ?>"
                width="120"
                class="img-thumbnail mb-2">

        <?php else: ?>

            <p>Chưa có ảnh</p>

        <?php endif; ?>

        <input
            type="file"
            name="image"
            class="form-control">

    </div>
    <div class="mb-3">

    <label class="form-label">
        Thêm hình ảnh phụ
    </label>

    <input
        type="file"
        name="images[]"
        class="form-control"
        multiple
        accept="image/*">

    <small class="text-muted">
        Có thể chọn nhiều hình ảnh.
    </small>

</div>
    <div class="mb-3">

    <label class="form-label">
        Hình ảnh phụ hiện tại
    </label>

    <div class="d-flex flex-wrap gap-3">

        <?php if (!empty($gallery)): ?>

            <?php foreach ($gallery as $item): ?>

                <div class="text-center">

                    <img
                        src="../../../uploads/products/<?= htmlspecialchars($item["image"]) ?>"
                        width="120"
                        height="120"
                        class="img-thumbnail"
                        style="object-fit: cover;">

                    <br>

                    <a
                        href="delete.php?id=<?= $item["id"] ?>&product_id=<?= $product->id ?>"
                        class="btn btn-danger btn-sm mt-2"
                        onclick="return confirm('Bạn có chắc muốn xóa hình ảnh này?')">

                        <i class="bi bi-trash"></i>
                        Xóa

                    </a>

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

                    <label class="form-label">Mô tả</label>

                    <textarea
                        name="description"
                        rows="5"
                        class="form-control"><?= htmlspecialchars($product->description) ?></textarea>

                </div>

                <div class="mb-3">

                    <label class="form-label">Trạng thái</label>

                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="radio"
                            name="status"
                            value="1"
                            <?= $product->status == 1 ? "checked" : "" ?>>

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
                            <?= $product->status == 0 ? "checked" : "" ?>>

                        <label class="form-check-label">
                            Ẩn
                        </label>

                    </div>

                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i>
                    Cập nhật
                </button>

                <a href="index.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Quay lại
                </a>

            </form>

        </div>

    </div>

</div>

<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>