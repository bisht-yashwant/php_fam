<?php
namespace App\Core;

use PDO;

class Database {
    private static $pdo;

    public static function connect() {
        if (!self::$pdo) {

            $host = getEnvData('DB_HOST') ?: '127.0.0.1';
            $port = getEnvData('DB_PORT') ?: '3306';
            $dbname = getEnvData('DB_NAME') ?: 'test';

            $username = getEnvData('DB_USERNAME') ?: 'root';
            $password = getEnvData('DB_PASSWORD') ?: '';

            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

            $retries = 5;

            while ($retries > 0) {
                try {
                    self::$pdo = new PDO($dsn, $username, $password);
                    break;
                } catch (\PDOException $e) {
                    $retries--;
                    if ($retries === 0) {
                        throw $e;
                    }
                    sleep(2);
                }
            }

            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }

        return self::$pdo;
    }
}