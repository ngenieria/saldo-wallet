<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Auto Loader...
if (!file_exists(__DIR__.'/vendor/autoload.php')) {
    die("Error Crítico: No se encuentra la carpeta 'vendor'. Debes subir la carpeta 'vendor' completa a tu hosting.");
}

require __DIR__.'/vendor/autoload.php';

// Bootstrap the application...
(require __DIR__.'/bootstrap/app.php')
    ->handleRequest(Request::capture());
