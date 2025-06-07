<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Flash;
use App\Core\Config;
use App\Models\User;
use App\Core\Controller;

class HomeController extends Controller {
    public function home() {
        return $this->render('home', ['title' => 'Welcome!']);
    }

    public function dashboard() {
        $this->layout = false;
        $data = cache_get('users_list');
        if (!$data) {
            $data = User::findByEmail("admin@admin.com");
            cache_put('users_list', $data, 600); // cache for 10 mins
        }
        return $this->render('home', ['title' => 'Welcome!']);
    }
}



