<?php

class Request
{
    public $method;
    public $uri;
    public $body;
    public $params;
    public $server;
    public $files;
    public $cookies;
    public $session;
    public $headers;

    public function __construct()
    {
        $_SESSION['csrf'] = md5(uniqid(mt_rand(), true));
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->body = $this->getRequestBody();
        $this->params = $_GET;
        $this->server = $_SERVER;
        $this->files = $_FILES;
        $this->cookies = $_COOKIE;
        $this->session = $_SESSION;
        $this->headers = getallheaders();
    }

    private function getRequestBody()
    {
        if (isset($_POST) && !empty($_POST)) return $_POST;
        return json_decode(file_get_contents('php://input'), true);
    }

    public function hasParam(string $paramKey)
    {
        return isset($this->params[$paramKey]);
    }

    public function getValue(string $key)
    {
        if (isset($this->params[$key])) {
            return $this->params[$key];
        }
        if (isset($this->body[$key])) {
            return $this->body[$key];
        }
        return null;
    }

    private function getCsrfToken()
    {
        return $_SESSION['csrf'];
    }

    public function checkCsrfToken()
    {
        $tokenInputName = '__token';
        if (!isset($this->body[$tokenInputName]) || $this->body[$tokenInputName] !== $this->getCsrfToken()) {
            throw new Exception('Invalid CSRF token', 403);
        }
    }

    public function getBearerToken()
    {
        $headers = $this->headers;
        if (!isset($headers['Authorization'])) {
            return null;
        }
        $authHeader = $headers['Authorization'];
        return preg_replace('/Bearer\s/', '', $authHeader);
    }
}
