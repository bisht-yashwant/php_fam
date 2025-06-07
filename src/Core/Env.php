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
