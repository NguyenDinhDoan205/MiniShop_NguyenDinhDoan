```php
<?php

require_once "../../../dao/CategoryDAO.php";
require_once "../../../models/Category.php";

$pageTitle = "Thêm danh mục";

$categoryDAO = new CategoryDAO();

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $catename = trim($_POST["catename"] ?? "");
    $slug = trim($_POST["slug"] ?? "");
    $status = isset($_POST["status"]) ? (int)$_POST["status"] : 1;

    if ($catename == "") {

        $error = "Vui lòng nhập tên danh mục.";

    } elseif ($slug == "") {

        $error = "Vui lòng nhập slug.";

    } else {

        $image = "";

        if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {

            $fileName = $_FILES["image"]["name"];
            $fileTmp = $_FILES["image"]["tmp_name"];
            $fileSize = $_FILES["image"]["size"];
            $extension = strtolower(
                pathinfo($fileName, PATHINFO_EXTENSION)
            );

            $allowed = ["jpg", "jpeg", "png", "gif", "webp"];

            if (!in_array($extension, $allowed)) {

                $error = "Chỉ được upload file JPG, JPEG, PNG, GIF hoặc WEBP.";

            } elseif ($fileSize > 200 * 1024) {

                $error = "Kích thước hình ảnh không được vượt quá 200KB.";

            } else {

                $uploadDir = "../../../uploads/categories/";

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $image = time() . "_" . $fileName;
                if (!move_uploaded_file(
                    $fileTmp,
                    $uploadDir . $image
                )) {

                    $error = "Upload hình ảnh thất bại.";
                }
            }
        }

        if ($error == "") {

            $category = new Category(
                $catename,
                $slug,
                $image,
                "",
                $status
            );

            if ($categoryDAO->insert($category)) {

                header("Location: index.php");
                exit;

            } else {

                $error = "Thêm danh mục thất bại.";
            }
        }
    }
}

ob_start();

?>

<div class="card">

    <div class="card-header">
        <h4>Thêm danh mục</h4>
    </div>

    <div class="card-body">

        <?php if ($error != ""): ?>

            <div class="alert alert-danger">
                <?= $error ?>
            </div>

        <?php endif; ?>

        <form
            method="post"
            enctype="multipart/form-data">

            <div class="mb-3">

                <label class="form-label">
                    Tên danh mục
                </label>

                <input
                    type="text"
                    name="catename"
                    class="form-control"
                    value="<?= htmlspecialchars($_POST["catename"] ?? "") ?>">

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Slug
                </label>

                <input
                    type="text"
                    name="slug"
                    class="form-control"
                    value="<?= htmlspecialchars($_POST["slug"] ?? "") ?>">

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Hình ảnh
                </label>

                <input
                    type="file"
                    name="image"
                    class="form-control"
                    accept="image/*">

            </div>

            <div class="mb-3">

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

            <button
                type="submit"
                class="btn btn-success">

                <i class="bi bi-save"></i>
                Lưu

            </button>

            <a
                href="index.php"
                class="btn btn-secondary">

                Quay lại

            </a>

        </form>

    </div>

</div>

<?php

$content = ob_get_clean();

include "../layouts/master.php";

?>
```
