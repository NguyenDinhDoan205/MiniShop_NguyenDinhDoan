<?php

spl_autoload_register(function ($className) {
    $folders = [
        __DIR__ . "/controllers/Admin",
        __DIR__ . "/controllers/Client",
        __DIR__ . "/controllers",
        __DIR__ . "/dao",
        __DIR__ . "/models",
        __DIR__ . "/middleware",
        __DIR__ . "/config"
    ];
    $parts = explode("\\", $className);

    $className = end($parts);
    foreach ($folders as $folder) {

        $file = $folder . "/" . $className . ".php";

        if (file_exists($file)) {

            require_once $file;

            return;
        }
    }
    error_log(
        "Autoload không tìm thấy class: " . $className
    );
});