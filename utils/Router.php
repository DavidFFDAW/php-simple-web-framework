<?php

class Router
{
    protected $request = null;
    protected $routes = []; // stores routes

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function setRoute($method, $url, $controllerAndMethod)
    {
        $this->routes[$method][APP_HOST . $url] = array(
            'controller' => $controllerAndMethod[0],
            'method' => $controllerAndMethod[1]
        );
    }

    public function get(string $url, $controllerAndMethod)
    {
        $this->setRoute('GET', $url, $controllerAndMethod);
    }

    public function post(string $url, $controllerAndMethod)
    {
        $this->setRoute('POST', $url, $controllerAndMethod);
    }

    public function put(string $url, $controllerAndMethod)
    {
        $this->setRoute('PUT', $url, $controllerAndMethod);
    }

    public function delete(string $url, $controllerAndMethod)
    {
        $this->setRoute('DELETE', $url, $controllerAndMethod);
    }

    protected function callControllerMethod($controller, $method, $params = [])
    {
        if (method_exists($controller, $method) && is_callable([$controller, $method])) {
            $controller->$method($params);
            exit;
        }
    }

    public function group($middlewares)
    {
        foreach ($middlewares as $Middleware) {
            $middleObject = new $Middleware();
            $shouldKeep = $middleObject->validate($this->request);
        }
    }

    public function matchRoute()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];

        if ($method === 'POST') {
            $this->request->checkCsrfToken();
        }

        if (!isset($this->routes[$method]) || empty($this->routes[$method])) {
            throw new Exception('Route not found or method not allowed', 405);
        }

        foreach ($this->routes[$method] as $routeUrl => $target) {
            $controller = $target['controller'];
            $controllerMethod = $target['method'];
            // Use named subpatterns in the regular expression pattern to capture each parameter value separately
            $pattern = preg_replace('/\/:([^\/]+)/', '/(?P<$1>[^/]+)', $routeUrl);

            $Controller = new $controller($this->request);

            if ($routeUrl === $url) {
                return $this->callControllerMethod($Controller, $controllerMethod);
            }

            if (preg_match('#^' . $pattern . '$#', $url, $matches)) {
                // Pass the captured parameter values as named arguments to the target function

                /*
                 Cambiar todo esto con middlewares y con controllers
                */
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY); // Only keep named subpattern matches
                return $this->callControllerMethod($Controller, $controllerMethod, $params);
            }

            throw new Exception('Route not found', 404);
        }
    }
}
