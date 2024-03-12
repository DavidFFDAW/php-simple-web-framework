<?php
class Autoload
{
    public static function registerLoad()
    {
        spl_autoload_register(function ($class) {
            if (file_exists(DIR . 'models/' . $class . '.php')) {
                require_once DIR . 'models/' . $class . '.php';
            }
            if (file_exists(DIR . 'controllers/' . $class . '.php')) {
                require_once DIR . 'controllers/' . $class . '.php';
            }
            if (file_exists(DIR . 'db/' . $class . '.php')) {
                require_once DIR . 'db/' . $class . '.php';
            }
            if (file_exists(DIR . 'utils/' . $class . '.php')) {
                require_once DIR . 'utils/' . $class . '.php';
            }
        });
    }
}
