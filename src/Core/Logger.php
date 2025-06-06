<?php

namespace App\Core;

class Logger {
    protected static string $logFile = __DIR__ . '/../storage/logs/app.log';

    public static function info(string $message): void {
        self::write('INFO', $message);
    }

    public static function error(string $message): void {
        self::write('ERROR', $message);
    }

    protected static function write(string $level, string $message): void {
        $timestamp = date('Y-m-d H:i:s');
        $log = "[$timestamp] [$level] $message" . PHP_EOL;
        file_put_contents(self::$logFile, $log, FILE_APPEND | LOCK_EX);
    }
}
