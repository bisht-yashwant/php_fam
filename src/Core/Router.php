<?php 

namespace App\Core;
use App\Core\Auth;

class Router {
    protected $routes = [];
    protected $publicRoutes = ['/login', '/logout', '/signup'];

    public function __construct() {
        $routes = require __DIR__ . '/../Url/PublicRoutes.php';
        if (is_array($routes)) {
            $this->publicRoutes = array_merge($this->publicRoutes, $routes);
        }
    }

    public function action($uri, $action, $roles = []) {
        $this->routes[$uri] = [
            'action' => $action,
            'roles_has_permissions' => $roles,
        ];
        return $this;
    }

    public function method($method) {
        $lastUri = array_key_last($this->routes);
        $this->routes[$lastUri]['method'] = is_array($method) ? array_map('strtoupper', $method) : strtoupper($method);
        return $this;
    }

    public function dispatch($method, $uri, $roles = []) {
        $uri = ($uri == '')? '/' : $uri;

        if (isset($this->routes[$uri])) {
            if (!Auth::check() && !in_array($uri, $this->publicRoutes)) {
                header('Location: /login');
                exit;
            }

            $routesUri = $this->routes[$uri];
            if (!empty($routesUri['roles_has_permissions']) && !in_array(Auth::role(), $routesUri['roles_has_permissions'])) {
                http_response_code(403);
                require __DIR__ . '/../Config/AccessDeniedPage.php';
                exit;
            }
            
            if (!empty($routesUri['method'])) {
                $allowedMethods = (array) $routesUri['method']; // Cast to array if it's a string
                if (!in_array(strtoupper($method), $allowedMethods)) {
                    http_response_code(403);
                    exit("Method not allowed");
                }
            }


            $action = $routesUri['action'];

            if (is_callable($action)) {
                return $action();
            }

            if (is_string($action)) {
                [$controller, $method] = explode('@', $action);
                $controller = 'App\\Controllers\\' . $controller . 'Controller';
                $controllerInstance = new $controller();
                return $controllerInstance->$method();
            }
        }

        http_response_code(404);
        require __DIR__ . '/../Config/404.php';
    }
}
