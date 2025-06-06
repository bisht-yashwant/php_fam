<?php 

namespace App\Core;
use App\Core\Auth;

class Router {
    protected $routes = [];

    public function action($uri, $action) {
        $this->routes[$uri] = $action;
    }

    public function dispatch($method, $uri) {
        $uri = ($uri == '')? '/' : $uri;

        if (isset($this->routes[$uri])) {
            if (!Auth::check() && $uri != "/login") {
                header('Location: /login');
                exit;
            }
            $action = $this->routes[$uri];

            if (is_callable($action)) {
                return $action();
            }

            if (is_string($action)) {
                [$controller, $method] = explode('@', $action);
                $controller = "App\\Controllers\\$controller";
                $controllerInstance = new $controller();
                return $controllerInstance->$method();
            }
        }

        http_response_code(404);
        require __DIR__ . '/../Config/404.php';
    }
}
