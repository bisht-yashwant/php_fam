<?php

namespace App\Core;

class Controller {
    public string $useLayout = "layout";
    public bool $layout = true;
    public bool $navbar = true;
    public string $content = '';
    public array $menuItems = [];

    public function __construct() {
        $this->menuItems = require __DIR__ . '/../Config/MenuItems.php';
    }

    public function render(string $view, array $data = []): void {
        extract($data);

        // Get the controller class name without namespace
        $controllerClass = get_class($this);
        $controllerName = substr(strrchr($controllerClass, '\\'), 1); // e.g., AuthController
        $folderName = str_replace('Controller', '', $controllerName); // e.g., "Auth"

        $viewPath = __DIR__ . '/../Views/' . $folderName . '/' . $view . '.php';

        if (file_exists($viewPath)) {
            ob_start();
            include $viewPath;
            $this->content = ob_get_clean();

            if ($this->layout) {
                include __DIR__ . '/../Views/Layout/' . $this->useLayout . '.php'; // You should have a layout file
            } else {
                echo $this->content;
            }
        } else {
            echo "View '{$folderName}/{$view}.php' not found.";
        }
    }
}
