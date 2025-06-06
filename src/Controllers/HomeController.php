<?php

namespace App\Controllers;
use App\Core\View;
use App\Core\Config;


class HomeController {
    public function index() {
        return View::make('home', ['title' => 'Welcome!']);
    }
}





// Set global values
Config::set('app_name', 'Tarif.co');
Config::set('debug', true);
Config::set('base_url', 'http://localhost');

