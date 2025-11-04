<?php 

class Router
{
    protected array $routes = ['GET'=>[]
    ];
    
    public function get(string $path, callable $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        // If the route does not exist, return 404
        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        $handler = $this->routes[$method][$uri];

        if (!is_callable($handler)) {
            throw new \Exception("Route handler for {$uri} is not callable");
        }

        call_user_func($handler);
    }
}
