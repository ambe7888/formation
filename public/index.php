<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Adjust script name for Laravel when rewriting from a subdirectory
if (isset($_SERVER['SCRIPT_NAME']) && str_contains($_SERVER['SCRIPT_NAME'], '/public/index.php')) {
    if (isset($_SERVER['REQUEST_URI']) && !str_contains($_SERVER['REQUEST_URI'], '/public/')) {
        $_SERVER['SCRIPT_NAME'] = str_replace('/public/index.php', '/index.php', $_SERVER['SCRIPT_NAME']);
        $_SERVER['PHP_SELF'] = str_replace('/public/index.php', '/index.php', $_SERVER['PHP_SELF']);
    }
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';


// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
