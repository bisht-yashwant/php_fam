<?php

namespace App\Core;
use App\Core\Logger;

class Log {
    public static function __callStatic($method, $args) {
        if (method_exists(Logger::class, $method)) {
            return Logger::$method(...$args);
        }

        throw new Exception("Logger method '$method' not found.");
    }
}
