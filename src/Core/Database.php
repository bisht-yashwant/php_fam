<?php
namespace App\Core;

use PDO;

class Database {
    private static $pdo;

    public static function connect() {
        if (!self::$pdo) {
            self::$pdo = new PDO(getEnvData('DB_HOST'), getEnvData('DB_USERNAME'), getEnvData('DB_PASSWORD'));
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$pdo;
    }
}
