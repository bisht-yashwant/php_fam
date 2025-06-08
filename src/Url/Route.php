<?php

use App\Core\Router;

$router = new Router();

$router->action('/', 'Auth@index');
$router->action('/signup', 'Auth@signup');
$router->action('/login', 'Auth@login');
$router->action('/logout', 'Auth@logout');

$router->action('/forgot-password', 'Auth@forgotPassword');
$router->action('/change-password', 'Auth@changePassword');

$router->action('/home', 'Home@home');
$router->action('/dashboard', 'Home@dashboard')->permission(['view_users', 'edit_posts'], 'permissions')->method(['GET', 'POST']);
$router->action('/public', 'Auth@dashboard');

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['PATH_INFO']?? '/');