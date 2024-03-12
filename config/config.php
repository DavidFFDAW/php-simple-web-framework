<?php

define('DEBUG', true);
define('LANG', 'es');
define('APP_TITLE', 'App');
define('APP_HOST', '/web-framework');
define('APP_URL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/' . APP_HOST . '/');

ini_set('display_errors', DEBUG);
ini_set('display_startup_errors', DEBUG);
error_reporting(DEBUG ? E_ALL : 0);

define('VIEWS', DIR . 'views' . DIRECTORY_SEPARATOR);

function dd($data)
{
    die('<pre>' . print_r($data, true) . '</pre>');
}

function view($view, $data = [])
{
    // errors.page-500
    $replaced = str_replace('.', DIRECTORY_SEPARATOR, $view);
    $file = VIEWS . $replaced . '.php';

    if (file_exists($file)) {
        extract($data);
        require_once $file;
    } else {
        throw new Exception('View not found');
    }
}
