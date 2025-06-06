<?php
namespace App\Models;

use PDO;
use App\Core\Config;

class User {
    public static function db(): PDO {
        $host = getEnvData('DB_HOST');
        $userName = getEnvData('DB_USERNAME');
        $password = getEnvData('DB_PASSWORD');
        return new PDO(
            $host,
            $userName,
            $password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    public static function findByEmail(string $email): ?array {
        $stmt = self::db()->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function findById(int $id): ?array {
        $stmt = self::db()->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function getUserRole($user_id, string $permission): bool {
        if (!$permission) return false;
        $stmt = self::db()->prepare("SELECT 1
            FROM user_role ur
            JOIN role_permission rp ON ur.role_id = rp.role_id
            JOIN permissions p ON rp.permission_id = p.id
            WHERE ur.user_id = ? AND p.name = ?
            LIMIT 1");
        $stmt->execute([$user_id, $permission]);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }
}