<?php

namespace App\Core;

use App\Core\Session;
use App\Models\User;

class Auth {
    public static function check(): bool {
        return !!Session::get('user_id');
    }

    public static function user(): ?array {
        $userId = Session::get('user_id');
        return $userId ? User::findById($userId) : null;
    }

    public static function login(array $user): void {
        Session::set('user_id', $user['id']);
        session_regenerate_id(true);
    }

    public static function logout(): void {
        Session::destroy();
    }

    public static function role(string $requiredRole): bool {
        $user = self::user();
        return $user && $user['role'] === $requiredRole;
    }

    public static function hasRole(array $roles): bool {
        $user = self::user();
        return $user && in_array($user['role'], $roles);
    }
    public static function userId(){
        $user = self::user();
    	return $user['id']?? '';
    }

	public static function can(string $permission): bool {
	    return User::getUserRole(self::userId(), $permission);
	}
}
