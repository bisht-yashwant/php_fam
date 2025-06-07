<?php

use App\Core\Router;

$router = new Router();

$router->action('/signup', 'AuthController@signup');
$router->action('/login', 'AuthController@login');
$router->action('/logout', 'AuthController@logout');
$router->action('/dashboard', 'AuthController@dashboard');

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);