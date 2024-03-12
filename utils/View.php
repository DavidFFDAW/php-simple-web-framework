<?php

class View
{
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);
        $replaced = str_replace('.', DIRECTORY_SEPARATOR, $view);
        $file = VIEWS . $replaced . '.php';

        $title = $args['title'] ?? APP_TITLE; // it is getting passed to the view
        require VIEWS . 'commons/head.php';

        if (!file_exists($file)) throw new Exception('View not found', 404);

        ob_start();
        require $file;
        require VIEWS . 'commons/footer.php';
        $content = ob_get_clean();
        return die($content);
    }
}
