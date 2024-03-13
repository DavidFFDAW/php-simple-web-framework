<?php

class Router
{
    protected $request = null;
    protected $routes = []; // stores routes

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function setRoute($method, $url, $controllerAndMethod, $middlewares = [])
    {
        $this->routes[$method][APP_HOST . $url] = array(
            'controller' => $controllerAndMethod[0],
            'method' => $controllerAndMethod[1],
            'middlewares' => $middlewares
        );
    }

    public function get(string $url, $controllerAndMethod, $middlewares = [])
    {
        $this->setRoute('GET', $url, $controllerAndMethod, $middlewares);
    }

    public function post(string $url, $controllerAndMethod, $middlewares = [])
    {
        $this->setRoute('POST', $url, $controllerAndMethod, $middlewares);
    }

    public function put(string $url, $controllerAndMethod, $middlewares = [])
    {
        $this->setRoute('PUT', $url, $controllerAndMethod, $middlewares);
    }

    public function delete(string $url, $controllerAndMethod, $middlewares = [])
    {
        $this->setRoute('DELETE', $url, $controllerAndMethod, $middlewares);
    }

    protected function callControllerMethod($controller, $method, $params = [])
    {
        if (method_exists($controller, $method) && is_callable([$controller, $method])) {
            $controller->$method($params);
            exit;
        }
    }

    // public function group($middlewares)
    // {
    //     foreach ($middlewares as $Middleware) {
    //         $middleObject = new $Middleware();
    //         $shouldKeep = $middleObject->validate($this->request);
    //     }
    // }

    public function group($middlewares, $callback)
    {
        return $callback($middlewares);
    }

    private function searchAndGetRoute($method, $url)
    {
        if (!isset($this->routes[$method]) || empty($this->routes[$method])) {
            throw new Exception('Route not found or method not allowed', 405);
        }

        foreach ($this->routes[$method] as $routeUrl => $target) {
            $controller = $target['controller'];
            $controllerMethod = $target['method'];
            $middlewares = $target['middlewares'];
            $pattern = preg_replace('/\/:([^\/]+)/', '/(?P<$1>[^/]+)', $routeUrl);

            if ($routeUrl === $url) {
                return [
                    'controller' => $controller,
                    'controllerMethod' => $controllerMethod,
                    'middlewares' => $middlewares,
                    'params' => [],
                ];
            }

            if (preg_match('#^' . $pattern . '$#', $url, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY); // Only keep named subpattern matches
                return [
                    'controller' => $controller,
                    'controllerMethod' => $controllerMethod,
                    'middlewares' => $middlewares,
                    'params' => $params
                ];
            }
        }

        throw new Exception('Route ' . $url . ' not found', 404);
    }

    public function matchRoute()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];

        if ($method === 'POST') {
            $this->request->checkCsrfToken();
        }

        $foundRoute = $this->searchAndGetRoute($method, $url);

        if (!empty($foundRoute['middlewares'])) {
            foreach ($foundRoute['middlewares'] as $Middleware) {
                $middleObject = new $Middleware();
                $shouldKeep = $middleObject->validate($this->request);
                if (!$shouldKeep) break;
            }
        }

        $controllerInstance = new $foundRoute['controller']($this->request);
        return $this->callControllerMethod($controllerInstance, $foundRoute['controllerMethod'], $foundRoute['params']);
    }
}
