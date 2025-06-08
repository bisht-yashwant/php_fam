#!/usr/bin/env php
<?php

// This line includes your bootstrap or autoloader.
require __DIR__ . '/vendor/autoload.php';

// Or if you're using Composer, use autoload
// require __DIR__ . '/vendor/autoload.php';

use App\Core\Console;

// Capture the CLI arguments
$argv = $_SERVER['argv']; // like: ['cli.php', 'serve']
$argc = $_SERVER['argc'];

// Get the command name
$command = $argv[1] ?? null;

// Create a Console object and run the command
$console = new Console();
$console->run($command, array_slice($argv, 2));
