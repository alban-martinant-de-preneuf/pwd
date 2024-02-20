<?php

require_once 'vendor/autoload.php';

$router = new AltoRouter();

$router->setBasePath('/pwd');

$router->map('GET', '/', function () {
    echo "Hello homepage";
}, 'home');

$router->map('GET', '/product', function () {
    echo "Hello product list";
}, 'about');

$router->map('GET', '/product/[i:id]', function ($id) {
    echo "Hello product with id : $id";
}, 'product');

// match current request url
$match = $router->match();
// call closure or throw 404 status`
if (is_array($match) && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    // no route was matched
    header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
}
