<?php

use App\Core\Router;

$router = new Router();

$router->action('/', 'Auth@index');
$router->action('/signup', 'Auth@signup');
$router->action('/login', 'Auth@login', ['admin', 'user']);
$router->action('/logout', 'Auth@logout');
$router->action('/dashboard', 'Home@dashboard', ['admin', 'user']);
$router->action('/home', 'Home@home');

$router->action('/public', 'Auth@dashboard');
$router->action('/home/user', 'Auth@dashboard');
$router->action('/home/user', 'Auth@home');

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);