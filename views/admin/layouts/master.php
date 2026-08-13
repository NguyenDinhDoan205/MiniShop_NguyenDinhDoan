<?php

require_once __DIR__ . '/../../../models/User.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../middleware/CsrfMiddleware.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
AuthMiddleware::handle();

CsrfMiddleware::generateToken();


?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? "Mini Shop Admin") ?></title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css"
        rel="stylesheet"
    >

</head>

<body class="bg-light">
    <header>
        <?php require_once __DIR__ . '/header.php'; ?>
    </header>
    <div class="container-fluid">

        <div class="row">
            <aside
                id="sidebar"
                class="col-md-2 bg-dark text-white min-vh-100 p-0"
            >
                <div class="p-3 border-bottom border-secondary">

                    <h5 class="mb-0">

                        <i class="bi bi-cart3 me-2"></i>

                        Mini Shop

                    </h5>
                </div>
                <div class="list-group list-group-flush">
                    <a
                        href="/MiniShop_NguyenDinhDoan/views/admin/dashboard.php"
                        class="list-group-item list-group-item-action bg-dark text-white border-secondary py-3"
                    >

                        <i class="bi bi-speedometer2 me-2"></i>

                        Dashboard

                    </a>
                    <a
                        href="/MiniShop_NguyenDinhDoan/views/admin/categories/index.php"
                        class="list-group-item list-group-item-action bg-dark text-white border-secondary py-3"
                    >

                        <i class="bi bi-grid me-2"></i>

                        Danh mục

                    </a>
                    <a
                        href="/MiniShop_NguyenDinhDoan/views/admin/brands/index.php"
                        class="list-group-item list-group-item-action bg-dark text-white border-secondary py-3"
                    >

                        <i class="bi bi-bookmark me-2"></i>

                        Thương hiệu

                    </a>
                    <a
                        href="/MiniShop_NguyenDinhDoan/views/admin/products/index.php"
                        class="list-group-item list-group-item-action bg-dark text-white border-secondary py-3"
                    >

                        <i class="bi bi-box me-2"></i>

                        Sản phẩm
                    </a>
                    <a
                        href="/MiniShop_NguyenDinhDoan/views/admin/customers/index.php"
                        class="list-group-item list-group-item-action bg-dark text-white border-secondary py-3"
                    >

                        <i class="bi bi-people me-2"></i>

                        Khách hàng

                    </a>
                    <a
                        href="/MiniShop_NguyenDinhDoan/views/admin/users/index.php"
                        class="list-group-item list-group-item-action bg-dark text-white border-secondary py-3"
                    >

                        <i class="bi bi-person me-2"></i>

                        Người dùng

                    </a>
                    <a
                        href="/MiniShop_NguyenDinhDoan/views/admin/orders/index.php"
                        class="list-group-item list-group-item-action bg-dark text-white border-secondary py-3"
                    >

                        <i class="bi bi-cart me-2"></i>

                        Đơn hàng

                    </a>
                    <a
                        href="/MiniShop_NguyenDinhDoan/views/admin/logout.php"
                        class="list-group-item list-group-item-action bg-dark text-danger border-secondary py-3"
                    >

                        <i class="bi bi-box-arrow-right me-2"></i>

                        Đăng xuất

                    </a>


                </div>

            </aside>


           
            <main
                id="mainContent"
                class="col-md-10 p-4"
            >

                <?= $content ?? "" ?>

            </main>


        </div>

    </div>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js">
    </script>


</body>

</html>