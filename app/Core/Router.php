<?php

class Router {
    private $routes = [];
    private static $instance = null;

    private function __construct() {}

    public static function create() {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    // TODO: routable route

    public function get($url, $controller, $name=null) {
        $route = $this->add("GET", $url, $controller, $name);
        return $this;
    }
    public function post($url, $controller, $name=null) {
        $route = $this->add("POST", $url, $controller, $name);
        return $instance;
    }
    public function put($url, $controller, $name=null) {
        $route = $this->add("PUT", $url, $controller, $name);
        return $this;
    }
    public function delete($url, $controller, $name=null) {
        $route = $this->add("DELETE", $url, $controller, $name);
        return $this;
    }

    public function only($role) {
        $this->routes[array_key_last($this->routes)]["role"] = $role;
    }

    public function getRoutes() {
        return $this->routes;
    }

    public function route($method, $url) {
        // dd("URI: $url, METHOD: $method");

        $route = $this->findRoute($method, $url);

        if ($route) {
            require_once $route["controller"];
        }
    }

    private function add($method, $url, $controller, $name=null) {
        $domain = explode("/", $controller)[0];
        $controller = explode("/", $controller)[1];    
        
        $route = [
            "method" => $method,
            "url" => $url,
            "controller" => ROOT . "app/Domains/$domain/Controllers/$controller.php",
            "name" => $name
        ];

        $this->routes[] = $route;

        return $route;
    }

    private function findRoute($method, $url) {
        foreach($this->routes as $route) {
            if ($route['method'] == $method && $route['url'] == $url) {
                return $route;
            }
        }
    }
}