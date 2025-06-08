<?php

namespace App\Models;

use App\Core\Model;

class User extends Model {
    protected static $fillable = ['id', 'name', 'email', 'password', 'is_active', 'token_expiry', 'password_reset_token'];

    protected static $table = 'users';

    public static function findByEmail(string $email) {
        return self::where('email', '=', $email)->one();
    }

    public static function findById(int $id) {
        return self::where('id', '=', $id)->one();
    }

    public static function getUserRole($user_id) {
        return self::from('user_role')
            ->leftJoin('roles', 'user_role.role_id', '=', 'roles.id')
            ->where('user_id', '=', $user_id)
            ->one() ?? null;
    }

    public static function createUser($name, $email, $password) {
        $user = new User([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT)
        ]);
        if ($user->save()) {
            return true;
        }

        // Debug info
        error_log("User insert failed: " . implode(", ", $stmt->errorInfo()));
        return false;
    }

}

