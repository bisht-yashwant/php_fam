<?php

namespace App\Controllers;

use DateTime;
use App\Core\Auth;
use App\Models\User;
use App\Core\Controller;


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
            verify_csrf_token();
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = User::findByEmail($email);
            if ($user && password_verify($password, $user->password)) {
                Auth::login($user);
                header('Location: /home');
                exit;
            } else {
                set_flash('error', 'Invalid credentials.');
            }
        }
        return $this->render('login', ['title' => 'Welcome!']);
    }

    public function forgotPassword() {
        $this->navbar = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $user = User::findByEmail($email);
            if ($user) {
                $token = bin2hex(random_bytes(16));

                // Set expiry to 1 hour from now
                $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

                $user->password_reset_token = $token;
                $user->token_expiry = $expiry;
                if($user->save()){
                    // Send reset email to the user
                    set_flash('success', getDomain() . "/change-password?token=$token");
                }
                
            } else {
                set_flash('error', 'Email not found!');
            }
        }
        return $this->render('forgotPassword', ['title' => 'Forgot Password!']);
    }
    public function changePassword() {
        $this->navbar = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password_reset_token = $_GET['token'];
            $userModel = User::find()->where('password_reset_token', '=', $password_reset_token)->one();
            
            if ($userModel) {
                $now = new DateTime();
                $expires = new DateTime($userModel->token_expiry);
                
                if ($now < $expires) {
                    $newPassword = $_POST['new-password'];
                    $userModel->password = password_hash($newPassword, PASSWORD_DEFAULT);
                    $userModel->password_reset_token = null;
                    $userModel->token_expiry = null;
                    $userModel->save();
                    set_flash('success', 'Password has been reset successfully.');
                } else {
                    set_flash('error', 'Token has expired.');
                }
            } else {
                set_flash('error', 'Invalid or expired token.');
            }
        }
        return $this->render('changePassword', ['title' => 'Forgot Password!']);
    }

    public function logout() {
        Auth::logout();
        header('Location: /login');
    }

    public function index() {
        // $this->useLayout = 'secondLayout';
        return $this->render('index', ['title' => 'Welcome!']);
    }
}
