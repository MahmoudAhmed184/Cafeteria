<?php

namespace App\Core;

class Router {
    private array $routes = [];
    private static ?Router $instance = null;

    private function __construct() {}

    public static function create(): Router {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function get(string $url, callable $action): self {
        $this->add('GET', $url, $action);
        return $this;
    }

    public function post(string $url, callable $action): self {
        $this->add('POST', $url, $action);
        return $this;
    }

    public function delete(string $url, callable $action): self {
        $this->add('DELETE', $url, $action);
        return $this;
    }

    public function put(string $url, callable $action): self {
        $this->add('PUT', $url, $action);
        return $this;
    }

    public function route(string $method, string $url): void {
        $route = $this->findRoute($method, $url);
        if ($route) {
            call_user_func($route['action']);
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
    }

    private function add(string $method, string $url, callable $action): void {
        $this->routes[] = [
            'method' => $method,
            'url' => $url,
            'action' => $action
        ];
    }

    private function findRoute(string $method, string $url): ?array {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['url'] === $url) {
                return $route;
            }
        }
        return null;
    }
}