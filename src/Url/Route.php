<?php

use App\Core\Router;

$router = new Router();

$router->action('/', 'Auth@index');
$router->action('/signup', 'Auth@signup');
$router->action('/login', 'Auth@login');
$router->action('/logout', 'Auth@logout');
$router->action('/dashboard', 'Home@dashboard')->permission(['view_users', 'edit_posts'], 'permissions')->method(['GET']);
$router->action('/home', 'Home@home');

$router->action('/public', 'Auth@dashboard');
$router->action('/home/user', 'Auth@dashboard');
$router->action('/home/user', 'Auth@home');

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);