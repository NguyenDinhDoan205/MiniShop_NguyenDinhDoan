<?php

define('APP_ENTRY', true);

require_once __DIR__ . '/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// =====================================================
// NHẬN REQUEST — PARSE TỪ PRETTY URL (.htaccess)
// =====================================================

$url = $_GET["url"] ?? "";
$url = trim($url, "/");
$segments = $url === "" ? [] : explode("/", $url);

$area       = $segments[0] ?? ($_GET["area"] ?? "admin");
$controller = $segments[1] ?? ($_GET["controller"] ?? "product");
$action     = $segments[2] ?? ($_GET["action"] ?? "index");
$id         = $segments[3] ?? ($_GET["id"] ?? null);

if ($area === "admin" && $controller === "login") {
    $controller = "auth";
    $action = "login";
}

if ($id !== null) {
    $_GET["id"] = $id;
}


// =====================================================
// AUTHENTICATION CHO ADMIN
// =====================================================

if ($area === "admin" && $controller !== "auth") {
    \Middleware\AuthMiddleware::handle();
}


// =====================================================
// GUEST MIDDLEWARE
// =====================================================

if (
    $area === "admin"
    && $controller === "auth"
    && $action === "login"
) {
    \Middleware\GuestMiddleware::handle();
}


// =====================================================
// CSRF TOKEN
// =====================================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    \Middleware\CsrfMiddleware::generateToken();
}


// =====================================================
// XÁC ĐỊNH CONTROLLER
// =====================================================

if ($area === "admin") {

    $controllerClass =
        "Controllers\\Admin\\" .
        ucfirst($controller) .
        "Controller";

} else {

    $controllerClass =
        "Controllers\\Client\\" .
        ucfirst($controller) .
        "Controller";
}

if (!class_exists($controllerClass)) {
    die(
        "Controller không tồn tại: " .
        htmlspecialchars($controllerClass)
    );
}

$controllerObject = new $controllerClass();

if (!method_exists($controllerObject, $action)) {
    die(
        "Action không tồn tại: " .
        htmlspecialchars($action)
    );
}

$result = $controllerObject->$action();

if (is_array($result)) {
    extract($result);
}


// =====================================================
// LOGOUT — không cần view, tự header()+exit bên trong Controller
// =====================================================

if (
    $area === "admin"
    && $controller === "auth"
    && $action === "logout"
) {
    exit;
}


// =====================================================
// TÌM VIEW TỰ ĐỘNG THEO QUY ƯỚC
// =====================================================
// Ưu tiên lần lượt các đường dẫn có thể có, để tương thích
// với các view cũ đang đặt tên số nhiều (products/) lẫn
// số ít (product/) — tránh phải sửa lại view cũ.

$possibleViewPaths = [
    // Quy ước mới: views/{area}/{controller}/{action}.php
    __DIR__ . "/views/{$area}/{$controller}/{$action}.php",

    // Quy ước cũ (số nhiều), ví dụ: views/admin/products/index.php
    __DIR__ . "/views/{$area}/{$controller}s/{$action}.php",

    // Trường hợp đặc biệt: auth/login => views/admin/login.php
    __DIR__ . "/views/{$area}/{$action}.php",
];

$viewFound = false;

foreach ($possibleViewPaths as $viewPath) {
    if (file_exists($viewPath)) {
        require $viewPath;
        $viewFound = true;
        break;
    }
}

if ($viewFound) {
    exit;
}
die(
    "Chưa cấu hình View cho: " .
    htmlspecialchars($area) .
    "/" .
    htmlspecialchars($controller) .
    "/" .
    htmlspecialchars($action) .
    "<br><br>Đã tìm ở các vị trí:<br>" .
    implode("<br>", array_map('htmlspecialchars', $possibleViewPaths))
);