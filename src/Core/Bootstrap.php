<?php

use App\Core\Logger;
use App\Core\Config;
use App\Core\Session;

Session::start();
Config::load(require __DIR__ . '/../Config/app.php');

$isProd = getEnvData('ENV') === 'prod';
if ($isProd === 'prod') {
    ini_set('display_errors', 0);
    error_reporting(0);
} else {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

set_exception_handler(function (Throwable $e) use ($isProd) {
    Logger::error("Uncaught Exception: {$e->getMessage()} in {$e->getFile()} on line {$e->getLine()}");

    http_response_code(500);
    if ($isProd) {
        echo "Internal Server Error";
    } else {
        echo "<pre>Exception: {$e->getMessage()} in {$e->getFile()} on line {$e->getLine()}</pre>";
    }
});

set_error_handler(function ($severity, $message, $file, $line) use ($isProd) {
    Logger::error("PHP Error: $message in $file on line $line");

    if (!$isProd) {
        echo "<pre>PHP Error: $message in $file on line $line</pre>";
    }

    return true;
});

register_shutdown_function(function () use ($isProd) {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        Logger::error("Fatal Error: {$error['message']} in {$error['file']} on line {$error['line']}");

        if (!$isProd) {
            echo "<pre>Fatal Error: {$error['message']} in {$error['file']} on line {$error['line']}</pre>";
        } else {
            echo "Internal Server Error";
        }
    }
});
