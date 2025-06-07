<?php

namespace App\Core {
    use App\Core\Logger;

    class Log {
        public static function __callStatic($method, $args) {
            if (method_exists(Logger::class, $method)) {
                return Logger::$method(...$args);
            }

            throw new \Exception("Logger method '$method' not found.");
        }
    }
}

namespace {
    function log_info($message) {
        return \App\Core\Logger::info($message);
    }

    function log_debug($message) {
        return \App\Core\Logger::debug($message);
    }

    function log_warning($message) {
        return \App\Core\Logger::warning($message);
    }

    function log_error($message) {
        return \App\Core\Logger::error($message);
    }

    function log_critical($message) {
        return \App\Core\Logger::critical($message);
    }
}
