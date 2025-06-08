<?php

namespace App\Core;

use App\Core\Auth;

class Router
{
    protected array $routes = [];
    protected array $publicRoutes = [''];

    public function __construct() {
        $extraRoutes = require __DIR__ . '/../Url/PublicRoutes.php';
        if (is_array($extraRoutes)) {
            $this->publicRoutes = array_merge($this->publicRoutes, array_map([$this, 'normalizeUri'], $extraRoutes));
        }
    }

    public function action(string $uri, callable|string $action): self {
        $uri = $this->normalizeUri($uri);
        $this->routes[$uri] = [
            'action' => $action,
            'permissions' => [],
            'permission_type' => 'roles', // 'roles' or 'permissions'
            'methods' => ['GET', 'POST'],
        ];
        return $this;
    }

    public function permission(array|string $permissions, string $type = 'roles'): self {
        $lastUri = array_key_last($this->routes);
        if ($lastUri !== null) {
            $this->routes[$lastUri]['permissions'] = (array)$permissions;
            $this->routes[$lastUri]['permission_type'] = $type;
        }
        return $this;
    }

    public function method(array|string $methods): self {
        $lastUri = array_key_last($this->routes);
        if ($lastUri !== null) {
            $this->routes[$lastUri]['methods'] = array_map('strtoupper', (array)$methods);
        }
        return $this;
    }

    public function dispatch(string $method, string $uri): mixed {
        $uri = $this->normalizeUri($uri);
        $method = strtoupper($method);

        if (!isset($this->routes[$uri])) {
            http_response_code(404);
            require __DIR__ . '/../Config/404.php';
            return null;
        }

        $route = $this->routes[$uri];

        // Validate request method
        if (!in_array($method, $route['methods'])) {
            http_response_code(405);
            echo "Method Not Allowed";
            return null;
        }

        // Auth check
        if (!in_array($uri, $this->publicRoutes) && !Auth::isAuthenticated()) {
            header('Location: /login');
            exit;
        }

        // Permission check
        if (!empty($route['permissions'])) {
            $granted = match ($route['permission_type']) {
                'roles' => Auth::hasRole($route['permissions']),
                'permissions' => $this->checkPermissions($route['permissions']),
                default => false,
            };

            if (!$granted) {
                http_response_code(403);
                require __DIR__ . '/../Config/AccessDeniedPage.php';
                return null;
            }
        }

        return $this->executeAction($route['action']);
    }

    protected function checkPermissions(array $permissions): bool {
        foreach ($permissions as $perm) {
            if (Auth::can($perm)) {
                return true;
            }
        }
        return false;
    }

    protected function executeAction(callable|string $action): mixed {
        if (is_callable($action)) {
            return $action();
        }

        if (is_string($action) && str_contains($action, '@')) {
            [$controllerName, $method] = explode('@', $action);
            $controllerClass = 'App\\Controllers\\' . $controllerName . 'Controller';

            if (!class_exists($controllerClass)) {
                http_response_code(500);
                echo "Controller class '$controllerClass' not found.";
                return null;
            }

            $controller = new $controllerClass();

            if (!method_exists($controller, $method)) {
                http_response_code(500);
                echo "Method '$method' not found in controller '$controllerClass'.";
                return null;
            }

            return $controller->$method();
        }

        throw new \Exception("Invalid route action format.");
    }

    protected function normalizeUri(string $uri): string {
        $uri = '/' . ltrim($uri, '/');
        return rtrim($uri, '/');
    }
}
