<?php

namespace App\Classes;

class Router
{
    private array $routes = [];
    private string $basePath = '';

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function setBasePath(string $basePath): void
    {
        $this->basePath = $basePath;
    }

    public function map(Route $route): void
    {
        $this->routes[] = $route;
    }

    public function match(string $method, string $uri): void
    {
        $uri = str_replace($this->basePath, '', $uri);
        $uri = rtrim($uri, '/');
        foreach ($this->routes as $route) {
            if ($route->getMethod() === $method && preg_match($route->getAssociedRegex(), $uri)) {
                $values = [];
                $uriSegments = explode('/', $uri);
                array_shift($uriSegments);
                foreach ($uriSegments as $key => $value) {
                    if (isset($route->getParams()[$key])) {
                        $values[] = $value;
                    }
                }
                call_user_func_array($route->getAction(), $values);
                return;
            }
        }
        echo "404 - Not found";
    }
}
