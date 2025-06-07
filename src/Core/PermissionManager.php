<?php

namespace App\Core;

use App\Models\Db;
use App\Models\User;

class PermissionManager
{
    protected static array $cache = [];

    public static function getPermissionsByRole(int $roleId): array {
        if (isset(self::$cache[$roleId])) {
            return self::$cache[$roleId];
        }

        $permissions = Db::select(['name'])
            ->from('permissions')
            ->innerJoin('role_permission', 'role_permission.permission_id', '=', 'permissions.id')
            ->where('role_permission.role_id', '=', $roleId)
            ->column();

        // Cache it for later use during this request
        self::$cache[$roleId] = $permissions;

        return $permissions;
    }

    public static function roleHasPermission(int $roleId, string $permissionName): bool {
        $permissions = self::getPermissionsByRole($roleId);
        return in_array($permissionName, $permissions);
    }

    public static function userCan(int $userId, string $permissionName): bool {
        $roleId = User::getUserRole($userId)->id;
        if (!$roleId) {
            return false;
        }
        return self::roleHasPermission($roleId, $permissionName);
    }

    public static function clearCache(int $roleId = null): void {
        if ($roleId === null) {
            self::$cache = [];
        } else {
            unset(self::$cache[$roleId]);
        }
    }
}
