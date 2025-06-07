<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\User;
use App\Core\Session;

class AuthController extends Controller {
    public function signup() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $password = password_hash($password, PASSWORD_DEFAULT);
            $user = User::findByEmail($email);
            if ($user && password_verify($password, $user['password'])) {
                Auth::login($user);
                header('Location: /dashboard');
                exit;
            } else {
                echo 'Invalid credentials';
            }
        }
        return $this->render('signup', ['title' => 'Welcome!']);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = User::findByEmail($email);
            if ($user && password_verify($password, $user['password'])) {
                Auth::login($user);
                header('Location: /dashboard');
                exit;
            } else {
                echo 'Invalid credentials';
            }
        }
        return $this->render('login', ['title' => 'Welcome!']);
    }

    public function logout() {
        Auth::logout();
        header('Location: /login');
    }

    public function dashboard() {
    	$data = cache_get('users_list');
		if (!$data) {
            $data = User::findByEmail("admin@admin.com");
		    cache_put('users_list', $data, 600); // cache for 10 mins
		}
        return $this->render('home', ['title' => 'Welcome!']);
    }
}
