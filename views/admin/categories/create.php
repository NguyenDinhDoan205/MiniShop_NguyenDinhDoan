<?php

require_once "../../../models/User.php";
require_once "../../../middleware/RoleMiddleware.php";
require_once "../../../middleware/CsrfMiddleware.php";
require_once "../../../dao/CategoryDAO.php";
require_once "../../../models/Category.php";

/*
 * Chỉ Admin mới được thêm Category
 * role = 1
 */
RoleMiddleware::requireRole(1);

/*
 * Tạo CSRF Token
 */
$csrfToken = CsrfMiddleware::generateToken();

$pageTitle = "Thêm danh mục";

$categoryDAO = new CategoryDAO();

$error = "";
$success = "";

/*
 * Xử lý POST
 */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /*
     * Kiểm tra CSRF
     */
    CsrfMiddleware::verify();

    /*
     * Lấy dữ liệu form
     */
    $catename = trim($_POST["catename"] ?? "");
    $slug = trim($_POST["slug"] ?? "");
    $status = isset($_POST["status"])
        ? (int)$_POST["status"]
        : 1;

    /*
     * Validate
     */
    if ($catename === "") {

        $error = "Vui lòng nhập tên danh mục.";

    } elseif ($slug === "") {

        $error = "Vui lòng nhập slug.";

    } else {

        $image = "";

        /*
         * Upload hình ảnh
         */
        if (
            isset($_FILES["image"]) &&
            $_FILES["image"]["error"] === 0
        ) {

            $fileName = $_FILES["image"]["name"];
            $fileTmp = $_FILES["image"]["tmp_name"];
            $fileSize = $_FILES["image"]["size"];

            $extension = strtolower(
                pathinfo($fileName, PATHINFO_EXTENSION)
            );

            $allowed = [
                "jpg",
                "jpeg",
                "png",
                "gif",
                "webp"
            ];

            /*
             * Kiểm tra extension
             */
            if (!in_array($extension, $allowed)) {

                $error =
                    "Chỉ được upload file JPG, JPEG, PNG, GIF hoặc WEBP.";

            /*
             * Kiểm tra dung lượng
             */
            } elseif ($fileSize > 200 * 1024) {

                $error =
                    "Kích thước hình ảnh không được vượt quá 200KB.";

            } else {

                /*
                 * Thư mục upload
                 */
                $uploadDir = "../../../uploads/categories/";

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                /*
                 * Đổi tên file
                 */
                $image =
                    time() . "_" .
                    basename($fileName);

                /*
                 * Di chuyển file
                 */
                if (
                    !move_uploaded_file(
                        $fileTmp,
                        $uploadDir . $image
                    )
                ) {

                    $error = "Upload hình ảnh thất bại.";
                }
            }
        }

        /*
         * Nếu không có lỗi thì thêm Category
         */
        if ($error === "") {

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

        <?php if ($error !== ""): ?>

            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

        <form
            action="create.php"
            method="POST"
            enctype="multipart/form-data"
        >

            <!-- CSRF TOKEN -->
            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars($csrfToken) ?>"
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
                        $_POST["catename"] ?? ""
                    ) ?>"
                    placeholder="Nhập tên danh mục"
                >

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
                        $_POST["slug"] ?? ""
                    ) ?>"
                    placeholder="Nhập slug"
                >

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Hình ảnh
                </label>

                <input
                    type="file"
                    name="image"
                    class="form-control"
                    accept="image/*"
                >

                <small class="text-muted">
                    JPG, JPEG, PNG, GIF, WEBP - tối đa 200KB
                </small>

            </div>
            

            <div class="mb-3">

                <label class="form-label">
                    Trạng thái
                </label>

                <select
                    name="status"
                    class="form-select"
                >

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
                class="btn btn-success"
            >

                <i class="bi bi-save"></i>
                Lưu

            </button>

            <a
                href="index.php"
                class="btn btn-secondary"
            >
                Quay lại
            </a>

        </form>

    </div>

</div>

<?php

$content = ob_get_clean();

include "../layouts/master.php";

?>