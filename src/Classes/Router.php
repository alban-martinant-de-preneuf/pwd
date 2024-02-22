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
        $uri = (substr($uri, -1) !== "/") ? $uri . "/" : $uri;
        foreach ($this->routes as $route) {
            if ($route->getMethod() === $method && preg_match($route->getAssociedRegex(), $uri)) {
                $values = [];
                $uriSegments = explode('/', $uri);
                array_shift($uriSegments);
                foreach ($uriSegments as $key => $value) {
                    if (isset($route->getParams()[$key])) {
                        $paramName = $route->getParams()[$key]['name'];
                        $values[$paramName] = $value;
                    }
                }

                $action = $route->getAction();

                if (is_string($action)) {
                    $action = explode('@', $action);
                    $controller = "App\\Controller\\" . $action[0];
                    var_dump($controller);
                    $controller = new $controller();
                    $method = $action[1];
                    $controller->$method(...$values);
                } else [
                    call_user_func_array($action, $values)
                ];
                return;
            }
        }
        echo "404 - Not found";
    }
}
