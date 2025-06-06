<?php

function getEnvData($key) {
    static $env = [];

    // Only parse once
    if (empty($env)) {
        $envContent = file_get_contents(__DIR__ . '/../../.env');
        $lines = explode("\n", $envContent);

        foreach ($lines as $line) {
            // Ignore comments and empty lines
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) continue;

            if (preg_match('/^([^=]+)=(.*)$/', $line, $matches)) {
                $k = trim($matches[1]);
                $v = trim($matches[2]);
                $env[$k] = $v;
                putenv("$k=$v"); // Optional: set as environment variable
            }
        }
    }

    return $env[$key] ?? null;
}

function cache_put($key, $data, $ttl = 3600) {
    $filename = __DIR__ . "/../storage/cache/{$key}.cache";
    $payload = [
        'data' => $data,
        'expires' => time() + $ttl
    ];
    file_put_contents($filename, serialize($payload));
}

function cache_get($key) {
    $filename = __DIR__ . "/../storage/cache/{$key}.cache";
    if (!file_exists($filename)) return null;

    $payload = unserialize(file_get_contents($filename));
    if ($payload['expires'] < time()) {
        unlink($filename);
        return null;
    }

    return $payload['data'];
}
