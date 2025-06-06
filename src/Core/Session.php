<?php

namespace App\Core;

class Session {
    public static function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set(string $key, mixed $value): void {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed {
        return $_SESSION[$key] ?? $default;
    }

    public static function forget(string $key): void {
        unset($_SESSION[$key]);
    }

    public static function destroy(): void {
        session_destroy();
    }
}
