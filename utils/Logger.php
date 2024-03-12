<?php

class Logger
{
    public static function log($data, $file = 'default')
    {
        file_put_contents(DIR . 'logs' . DIRECTORY_SEPARATOR . $file . '.log', print_r($data, true));
    }
}
