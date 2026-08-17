<?php
require_once __DIR__ . '/autoload.php';

session_start();

$area = $_GET["area"] ?? "admin";
$controller = $_GET["controller"] ?? "dashboard";
$action = $_GET["action"] ?? "index";
if ($area === "admin") {
    $controllerClass = "Controllers\\Admin\\" . ucfirst($controller) . "Controller";
} else {
    $controllerClass = "Controllers\\Client\\" . ucfirst($controller) . "Controller";
}

if ($area === "admin" && $controller === "auth" && $action === "login") {
    \Middleware\GuestMiddleware::handle();
}
\Middleware\CsrfMiddleware::generateToken();

if (!class_exists($controllerClass)) {
    die("Controller không tồn tại");
}
$controllerObject = new $controllerClass();

if (!method_exists($controllerObject, $action)) {
    die("Action không tồn tại");
}
$controllerObject->$action();
?>