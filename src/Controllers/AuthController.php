<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\User;
use App\Core\Session;

class AuthController extends Controller {
    public function signup() {
        $this->navbar = false;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // Simple validation
            if (!$name || !$email || !$password) {
                return $this->render('signup', [
                    'title' => 'Sign Up',
                    'error' => 'All fields are required.',
                ]);
            }

            // Check if email already exists
            if (User::findByEmail($email)) {
                return $this->render('signup', [
                    'title' => 'Sign Up',
                    'error' => 'Email already exists.',
                ]);
            }

            if (User::createUser($name, $email, $password)) {
                $user = User::findByEmail($email);
                Auth::login($user);
                header('Location: /dashboard');
                exit;
            } else {
                return $this->render('signup', [
                    'title' => 'Sign Up',
                    'error' => 'Something went wrong. Please try again.',
                ]);
            }
        }

        return $this->render('signup', ['title' => 'Sign Up']);
    }

    public function login() {
        $this->navbar = false;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = User::findByEmail($email);
            if ($user && password_verify($password, $user->password)) {
                Auth::login($user);
                header('Location: /home');
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

    public function index() {
        $this->useLayout = 'secondLayout';
        return $this->render('index', ['title' => 'Welcome!']);
    }
}
