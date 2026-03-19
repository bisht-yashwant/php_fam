<?php

function getEnvData($key, $default = null) {
    // 1. First check real environment (Docker / CI)
    $value = getenv($key);

    if ($value !== false) {
        return $value;
    }

    // 2. Fallback to .env file (local dev only)
    static $env = null;

    if ($env === null) {
        $env = [];

        $envPath = __DIR__ . '/../../.env';

        if (file_exists($envPath)) {
            $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach ($lines as $line) {
                $line = trim($line);

                if ($line === '' || str_starts_with($line, '#')) continue;

                [$k, $v] = array_map('trim', explode('=', $line, 2));

                // Remove quotes
                $v = trim($v, '"\'');

                $env[$k] = $v;
            }
        }
    }

    return $env[$key] ?? $default;
}

function getDomain() {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    return "$scheme://$host";
}