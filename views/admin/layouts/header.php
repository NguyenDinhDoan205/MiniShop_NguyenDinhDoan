<?php

require_once __DIR__ . "/../../../models/User.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = $_SESSION["user"] ?? null;

?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm py-2">
    <div class="container-fluid px-4 d-flex justify-content-between align-items-center">

        <button id="btnMenu" class="btn btn-outline-light border-0 me-3" type="button">
            <i class="bi bi-list fs-4"></i>
        </button>

        <div class="d-flex align-items-center gap-3">

            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center shadow-sm"
                 style="width: 42px; height: 42px;">
                <i class="bi bi-person-fill text-white fs-5"></i>
            </div>
            <div class="text-white">
                <div class="small text-white-50">Xin chào</div>
                <div class="fw-bold"><?= htmlspecialchars($user->fullname) ?></div>
            </div>
            <a href="/MiniShop_NguyenDinhDoan/views/admin/logout.php"
               class="btn btn-sm btn-outline-light ms-2">
                <i class="bi bi-box-arrow-right"></i> Đăng xuất
            </a>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>