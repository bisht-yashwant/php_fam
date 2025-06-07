<?php

namespace App\Core;

use App\Core\Auth;

class Router
{
    protected array $routes = [];
    protected array $publicRoutes = ['/login', '/logout', '/signup'];

    public function __construct() {
        $extraRoutes = require __DIR__ . '/../Url/PublicRoutes.php';
        if (is_array($extraRoutes)) {
            $this->publicRoutes = array_merge($this->publicRoutes, $extraRoutes);
        }
    }

    public function action(string $uri, $action, array $roles = []): self {
        $uri = $this->normalizeUri($uri);
        $this->routes[$uri] = [
            'action' => $action,
            'roles_has_permissions' => $roles,
            'method' => ['GET','POST'], // Default to GET
        ];
        return $this;
    }

    public function method($method): self {
        $lastUri = array_key_last($this->routes);
        if ($lastUri !== null) {
            $this->routes[$lastUri]['method'] = is_array($method)
                ? array_map('strtoupper', $method)
                : [strtoupper($method)];
        }
        return $this;
    }

    public function dispatch(string $method, string $uri): mixed {
        $uri = $this->normalizeUri($uri);

        if (!isset($this->routes[$uri])) {
            http_response_code(404);
            require __DIR__ . '/../Config/404.php';
            return null;
        }

        // Check method
        $route = $this->routes[$uri];
        $method = strtoupper($method);
        if (!in_array($method, $route['method'])) {
            http_response_code(405);
            echo "Method not allowed";
            return null;
        }

        // Check authentication
        if (!Auth::isAuthenticated() && !in_array($uri, $this->publicRoutes)) {
            // print_r("inside");
            // die;
            header('Location: /login');
            exit;
        }

        // Check authorization
        if (!empty($route['roles_has_permissions'])) {
            $hasPermission = false;
            foreach ($route['roles_has_permissions'] as $requiredPermission) {
                if (Auth::can($requiredPermission)) {
                    $hasPermission = true;
                    break;
                }
            }

            if (!$hasPermission) {
                http_response_code(403);
                require __DIR__ . '/../Config/AccessDeniedPage.php';
                return null;
            }
        }

        // Call the controller action or closure
        return $this->executeAction($route['action']);
    }

    protected function executeAction($action): mixed {
        if (is_callable($action)) {
            return $action();
        }

        if (is_string($action)) {
            [$controller, $method] = explode('@', $action);
            $controller = 'App\\Controllers\\' . $controller . 'Controller';

            if (!class_exists($controller)) {
                http_response_code(500);
                echo "Controller '$controller' not found.";
                return null;
            }

            $controllerInstance = new $controller();

            if (!method_exists($controllerInstance, $method)) {
                http_response_code(500);
                echo "Method '$method' not found in controller '$controller'.";
                return null;
            }

            return $controllerInstance->$method();
        }

        throw new \Exception("Invalid route action format");
    }

    protected function normalizeUri(string $uri): string {
        return '/' . trim($uri, '/');
    }
}
