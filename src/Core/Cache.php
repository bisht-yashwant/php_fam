<?php

use App\Core\Session;

class Cache {
    protected static string $dir = __DIR__ . '/../Storage/Cache/';

    public static function get(string $key): mixed {
        $file = self::filePath($key);
        if (!file_exists($file)) return null;

        $data = json_decode(file_get_contents($file), true);
        if ($data['expires_at'] < time()) {
            unlink($file);
            return null;
        }

        return $data['value'];
    }

    public static function set(string $key, mixed $value, int $ttl = 300): void {
        $file = self::filePath($key);
        $data = [
            'expires_at' => time() + $ttl,
            'value' => $value
        ];
        file_put_contents($file, json_encode($data), LOCK_EX);
    }

    protected static function filePath(string $key): string {
        return self::$dir . md5($key) . '.cache';
    }
}
