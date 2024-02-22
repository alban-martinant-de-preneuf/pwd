<?php

require_once 'vendor/autoload.php';

use App\Classes\Router;
use App\Classes\Route;

$router = new Router();

$router->setBasePath('/pwd');

$router->map(new Route('GET', '/', function () {
    echo "Hello homepage";
}));

$router->map(new Route('GET', '/product', function () {
    echo "Hello product list";
}));

$router->map(new Route('GET', '/product/[i:id]', function ($id) {
    echo "Hello product with id : $id";
}));

$router->match($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);