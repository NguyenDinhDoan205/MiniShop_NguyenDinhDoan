<?php

spl_autoload_register(function ($className) {

    $prefixes = [
        'Controllers\\' => __DIR__ . '/controllers/',
        'DAO\\'         => __DIR__ . '/dao/',
        'Models\\'      => __DIR__ . '/models/',
        'Middleware\\'  => __DIR__ . '/middleware/',
        'Config\\'      => __DIR__ . '/config/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {

        if (str_starts_with($className, $prefix)) {

            $relativeClass = substr(
                $className,
                strlen($prefix)
            );

            $file = $baseDir
                . str_replace('\\', '/', $relativeClass)
                . '.php';

            if (file_exists($file)) {
                require_once $file;
            }

            return;
        }
    }
});