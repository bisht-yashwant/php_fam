<?php

namespace App\Core{
    use App\Core\Session;
    use App\Models\User;

    class Auth {
        public static function isAuthenticated(): bool {
            $user = self::getUser();
            if (empty($user) || !$user->is_active) {
                self::logout();
                return false;
            }
            return true;
        }

        public static function getUser() {
            return User::findById(self::getUserId());
        }

        public static function login($user): void {
            if (!$user || empty($user->id)) {
                throw new \InvalidArgumentException("Invalid user object for login.");
            }
            session_regenerate_id(true);
            Session::set('user_id', $user->id);
        }

        public static function getUserId(): ?int {
            $user_id = Session::get('user_id');
            if (empty($user_id)) {
                self::logout();
                return false;
            }
            return $user_id;
        }

        public static function logout(): void {
            Session::destroy();
        }

        public static function getRole(): ?string {
            $userId = self::getUserId();
            return $userId ? User::getUserRole($userId)->name : null;
        }

        public static function hasRole(array|string $roles): bool {
            $role = self::getRole();
            if (is_array($roles)) {
                return in_array($role, $roles);
            }
            return $role === $roles;
        }

        public static function can(string $permission): bool {
            $userId = self::getUserId();
            if (!$userId) {
                return false;
            }
            return PermissionManager::userCan($userId, $permission);
        }
    }    
}

namespace {
    function is_logged_in(): bool {
        return \App\Core\Auth::isAuthenticated();
    }
    function current_user(): ?array {
        return \App\Core\Auth::getUser();
    }
    function current_user_id(): ?int {
        return \App\Core\Auth::getUserId();
    }
    function current_user_role(): ?string {
        return \App\Core\Auth::getRole();
    }
    function user_has_role(array|string $roles): bool {
        return \App\Core\Auth::hasRole($roles);
    }
    function user_can(string $permission): bool {
        return \App\Core\Auth::can($permission);
    }
}