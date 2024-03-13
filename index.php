<?php

$currentDir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
define('DIR', $currentDir);

require_once $currentDir . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once $currentDir . 'Autoload.php';

try {
    session_start();
    Autoload::registerLoad();
    $request = new Request();
    $router = new Router($request);

    $router->get('/', [HomeController::class, 'index']);

    $router->group([AuthMiddleware::class], function ($middlewares) use ($router) {
        $router->get('/admin', [HomeController::class, 'admin'], $middlewares);
    });

    // dispatch
    $router->matchRoute();
} catch (Exception $e) {
    die(View::render('errors.error-page', [
        'error' => $e
    ]));
} catch (Error $e) {
    die(View::render('errors.error-page', [
        'error' => $e
    ]));
}
