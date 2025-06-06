<?php

namespace App\Core;

class Controller {
    public function render($view, $data = []) {
        extract($data);

        // Get the controller class name without namespace
        $controllerClass = get_class($this);
        $controllerName = substr(strrchr($controllerClass, '\\'), 1); // e.g., AuthController
        $folderName = str_replace('Controller', '', $controllerName); // e.g., "Auth"

        $viewPath = __DIR__ . '/../Views/' . $folderName . '/' . $view . '.php';

        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "View '{$folderName}/{$view}.php' not found.";
        }
    }
}
