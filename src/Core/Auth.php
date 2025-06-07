<?php

namespace App\Core{
    use App\Core\Session;
    use App\Models\User;

    class Auth {
        public static function check(): bool {
            return !!Session::get('user_id');
        }

        public static function user(): ?array {
            return User::findById(self::userId());
        }

        public static function login($user): void {
            Session::set('user_id', $user->id);
            session_regenerate_id(true);
        }
        public static function userId(){
            $user_id = Session::get('user_id');
            if(empty($user_id)){ self::logout(); }
            return $user_id;
        }

        public static function logout(): void {
            Session::destroy();
        }

        public static function role() {
            return User::getUserRole(self::userId());
        }

        public static function hasRole(array $roles): bool {
            return in_array(self::role(), $roles);
        }

    	public static function can(string $permission): bool {
    	    return self::role() == $permission ;
    	}
    }    
}

namespace {
    function login_check() {
        return \App\Core\Auth::check();
    }
    function auth_user() {
        return \App\Core\Auth::user();
    }
    function user_role() {
        return \App\Core\Auth::role();
    }
    function user_has_role($roles) {
        return \App\Core\Auth::hasRole($roles);
    }
    function user_id() {
        return \App\Core\Auth::userId();
    }
    function user_can($permission) {
        return \App\Core\Auth::can($permission);
    }
}