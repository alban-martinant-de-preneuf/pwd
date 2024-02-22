<?php

namespace App\Classes;

class Route
{
    private array $params = [];
    private string $associedRegex;
    private $matchTypes = [
        'i'  => '[0-9]++',
        'a'  => '[0-9A-Za-z]++'
    ];

    public function __construct(
        private string $method,
        private string $uri,
        private mixed $action
    ) {
        $pathSegments = $this->getPathSegments();
        $this->associedRegex = $this->generateRegex($pathSegments);
    }

    private function getPathSegments(): array
    {
        $segments = explode('/', $this->uri);
        $segments = array_filter($segments, function ($segment) {
            return $segment !== '';
        });
        return $segments;
    }

    private function generateRegex(array $pathSegments): string
    {
        $regex = '/^\/';
        $i = 0;
        foreach ($pathSegments as $segment) {
            if (preg_match("/\[(.*?)\]/", $segment, $paramMatch)) {
                $this->extractParam($paramMatch[1], $i);
                $regex .= '\/(' . $this->matchTypes[$this->params[$i]['type']] . ')';
            } else {
                $regex .= $segment;
            }
            $i++;
        }
        $regex .= '$/';
        return $regex;
    }

    public function extractParam(string $paramMatch, int $i): void
    {
        $paramMatch = explode(':', $paramMatch);
        $paramType = $paramMatch[0];
        $paramName = $paramMatch[1];
        $param = [
            'type' => $paramType,
            'name' => $paramName
        ];
        $this->params[$i] = $param;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getAssociedRegex(): string
    {
        return $this->associedRegex;
    }

    public function getAction(): mixed
    {
        return $this->action;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
