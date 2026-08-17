<?php

error_reporting(E_ALL);
ini_set("display_errors", "1");
ini_set("display_startup_errors", "1");

require_once __DIR__ . "/autoload.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$area = $_GET["area"] ?? "admin";

$controller = $_GET["controller"] ?? "dashboard";

$action = $_GET["action"] ?? "index";

if ($area === "admin") {

    $controllerClass =
        "Controllers\\Admin\\" .
        ucfirst($controller) .
        "Controller";

} elseif ($area === "client") {

    $controllerClass =
        "Controllers\\Client\\" .
        ucfirst($controller) .
        "Controller";

} else {

    die("Area không hợp lệ.");
}


if (
    $area === "admin" &&
    $controller === "auth" &&
    $action === "login"
) {

    if (class_exists("\\Middleware\\GuestMiddleware")) {
        \Middleware\GuestMiddleware::handle();
    }
}
if (class_exists("\\Middleware\\CsrfMiddleware")) {

    \Middleware\CsrfMiddleware::generateToken();

} else {

    die(
        "Không tìm thấy Middleware\\CsrfMiddleware. " .
        "Kiểm tra middleware/CsrfMiddleware.php và autoload.php"
    );
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
        htmlspecialchars($controllerClass) .
        "::" .
        htmlspecialchars($action)
    );
}

$data = $controllerObject->$action();

if (is_array($data)) {

    extract($data);
}

$viewFile = null;

if ($area === "admin" && $controller === "product") {

    switch ($action) {

        case "index":

            $viewFile =
                __DIR__ .
                "/views/admin/products/index.php";

            break;

        case "create":

            $viewFile =
                __DIR__ .
                "/views/admin/products/create.php";

            break;

        case "edit":

            $viewFile =
                __DIR__ .
                "/views/admin/products/edit.php";

            break;

        case "detail":

            $viewFile =
                __DIR__ .
                "/views/admin/products/detail.php";

            break;

        default:

            die(
                "Chưa cấu hình view cho ProductController::" .
                htmlspecialchars($action)
            );
    }
}
if ($viewFile === null) {

    die(
        "Không xác định được View cho: " .
        htmlspecialchars($controllerClass) .
        "::" .
        htmlspecialchars($action)
    );
}


if (!file_exists($viewFile)) {

    die(
        "Không tìm thấy View: " .
        htmlspecialchars($viewFile)
    );
}


require $viewFile;