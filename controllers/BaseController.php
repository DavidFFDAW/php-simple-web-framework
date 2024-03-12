<?php

class BaseController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function getRequest()
    {
        return $this->request;
    }

    protected function checkMethod($method)
    {
        if ($this->request->method !== $method) {
            throw new Exception('Method used not allowed. Must be a ' . $method . ' request', 405);
        }
    }
}
