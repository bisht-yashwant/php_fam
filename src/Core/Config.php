<?php

namespace App\Core;

class Config {
    private static array $settings = [];

    public static function load(array $config): void {
        if (empty(self::$settings)) {
            self::$settings = $config;
        }
    }

    public static function get(string $key, mixed $default = null): mixed {
        return self::$settings[$key] ?? $default;
    }
}