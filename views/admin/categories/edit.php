<?php
require_once "../../../dao/CategoryDAO.php";
require_once "../../../models/Category.php";
require_once "../../../middleware/CsrfMiddleware.php";

$pageTitle = "Cập nhật danh mục";
$categoryDAO = new CategoryDAO();


$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

$category = $categoryDAO->findById($id);

if ($category == null) {
    header("Location: index.php");
    exit;
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
      CsrfMiddleware::verify();

    $catename = trim($_POST["catename"] ?? "");
    $slug = trim($_POST["slug"] ?? "");
    $status = isset($_POST["status"])
        ? (int)$_POST["status"]
        : 1;

    if ($catename == "") {

        $error = "Vui lòng nhập tên danh mục.";

    } elseif ($slug == "") {

        $error = "Vui lòng nhập slug.";

    } else {

        $image = $category->image;

        if (
            isset($_FILES["image"]) &&
            $_FILES["image"]["error"] == 0
        ) {

            $fileName = $_FILES["image"]["name"];
            $fileTmp = $_FILES["image"]["tmp_name"];
            $fileSize = $_FILES["image"]["size"];

            $extension = strtolower(
                pathinfo(
                    $fileName,
                    PATHINFO_EXTENSION
                )
            );

            $allowed = [
                "jpg",
                "jpeg",
                "png",
                "gif",
                "webp"
            ];

            if (!in_array($extension, $allowed)) {

                $error =
                    "Chỉ được upload JPG, JPEG, PNG, GIF hoặc WEBP.";

            } elseif ($fileSize > 200 * 1024) {

                $error =
                    "Kích thước hình ảnh không được vượt quá 200KB.";

            } else {

                $uploadDir =
                    "../../../uploads/categories/";

                if (!is_dir($uploadDir)) {
                    mkdir(
                        $uploadDir,
                        0777,
                        true
                    );
                }

                $newImage =
                    time() . "_" . $fileName;

                if (
                    move_uploaded_file(
                        $fileTmp,
                        $uploadDir . $newImage
                    )
                ) {

                    if (
                        !empty($category->image) &&
                        file_exists(
                            $uploadDir . $category->image
                        )
                    ) {

                        unlink(
                            $uploadDir . $category->image
                        );
                    }

                    $image = $newImage;

                } else {

                    $error =
                        "Upload hình ảnh thất bại.";
                }
            }
        }

        if ($error == "") {

            $category->catename = $catename;
            $category->slug = $slug;
            $category->image = $image;
            $category->status = $status;

            if ($categoryDAO->update($category)) {

                header("Location: index.php");
                exit;

            } else {

                $error =
                    "Cập nhật danh mục thất bại.";
            }
        }
    }
}

ob_start();

?>

<div class="card">

    <div class="card-header">

        <h4>
            Cập nhật danh mục
        </h4>

    </div>

    <div class="card-body">

        <?php if ($error != ""): ?>

            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

        <form action="edit.php" method="POST">

    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>"
    >


            <div class="mb-3">

                <label class="form-label">
                    Tên danh mục
                </label>

                <input
                    type="text"
                    name="catename"
                    class="form-control"
                    value="<?= htmlspecialchars(
                        $_POST["catename"]
                        ?? $category->catename
                    ) ?>">

            </div>
            <div class="mb-3">

                <label class="form-label">
                    Slug
                </label>

                <input
                    type="text"
                    name="slug"
                    class="form-control"
                    value="<?= htmlspecialchars(
                        $_POST["slug"]
                        ?? $category->slug
                    ) ?>">

            </div>
            <div class="mb-3">

                <label class="form-label">
                    Hình ảnh hiện tại
                </label>

                <br>

                <?php if (!empty($category->image)): ?>

                    <img
                        src="../../../uploads/categories/<?= htmlspecialchars($category->image) ?>"
                        width="120"
                        class="img-thumbnail">

                <?php else: ?>

                    <p class="text-muted">
                        Chưa có hình ảnh
                    </p>

                <?php endif; ?>

            </div>
            <div class="mb-3">

                <label class="form-label">
                    Chọn hình ảnh mới
                </label>

                <input
                    type="file"
                    name="image"
                    class="form-control"
                    accept="image/*">

                <small class="text-muted">
                    Không chọn ảnh mới thì giữ nguyên ảnh cũ.
                </small>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Trạng thái
                </label>

                <select
                    name="status"
                    class="form-select">

                    <option
                        value="1"
                        <?= $category->status == 1
                            ? "selected"
                            : "" ?>>

                        Hiển thị

                    </option>

                    <option
                        value="0"
                        <?= $category->status == 0
                            ? "selected"
                            : "" ?>>

                        Ẩn

                    </option>

                </select>

            </div>
            <button
                type="submit"
                class="btn btn-warning">

                <i class="bi bi-pencil"></i>

                Cập nhật

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

