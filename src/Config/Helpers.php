<?php

function cache_put($key, $data, $ttl = 3600) {
    $filename = __DIR__ . "/../Storage/Cache/{$key}.cache";
    $payload = [
        'data' => $data,
        'expires' => time() + $ttl
    ];
    file_put_contents($filename, serialize($payload));
}

function cache_get($key) {
    $filename = __DIR__ . "/../Storage/Cache/{$key}.cache";
    if (!file_exists($filename)) return null;

    $payload = unserialize(file_get_contents($filename));
    if ($payload['expires'] < time()) {
        unlink($filename);
        return null;
    }

    return $payload['data'];
}
