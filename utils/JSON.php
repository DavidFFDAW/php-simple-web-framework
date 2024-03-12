<?php

class JSON
{
    public static function success($datas)
    {
        http_response_code(200);
        $json = json_encode([
            'code' => 200,
            'data' => $datas
        ]);

        return die($json);
    }

    public static function error($data)
    {
        http_response_code(400);
        return die(json_encode([
            'code' => 400,
            'error' => $data
        ]));
    }

    public static function customResponse($code = 404, $datas = [], $message = '')
    {
        http_response_code($code);
        return die(json_encode([
            'code' => $code,
            'error' => $message,
            'data' => $datas,
        ]));
    }
}
