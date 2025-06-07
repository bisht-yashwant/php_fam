<?php

namespace App\Core;

class Logger {
    protected static string $logDir = __DIR__ . '/../Storage/Logs';

    public static function info(string $message): void {
        self::log('INFO', $message);
    }

    public static function debug(string $message): void {
        self::log('DEBUG', $message);
    }

    public static function warning(string $message): void {
        self::log('WARNING', $message);
    }

    public static function error(string $message): void {
        self::log('ERROR', $message);
    }

    public static function critical(string $message): void {
        self::log('CRITICAL', $message);
    }

    public static function log(string $level, string $message): void
    {
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0777, true);
        }

        $date = date('Y-m-d');
        $timestamp = date('Y-m-d H:i:s');
        $logFile = self::$logDir . "/$date.log";

        $formatted = "[$timestamp] [$level] $message" . PHP_EOL;

        file_put_contents($logFile, $formatted, FILE_APPEND);
    }
}
