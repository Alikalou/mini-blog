<?php

class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET'][] = [
            'pattern' => $path,
            'handler' => $handler,
        ];
    }

    public function post(string $path, callable $handler): void
    {
        $this->routes['POST'][] = [
            'pattern' => $path,
            'handler' => $handler,
        ];
    }

    public function dispatch(string $method, string $path): void
    {
        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $route) {
            $pattern = $route['pattern'];
            $handler = $route['handler'];

            // Convert '/posts/delete/{id}' → '#^/posts/delete/([^/]+)$#'
            $regex = '#^' . preg_replace('#\{[^/]+\}#', '([^/]+)', $pattern) . '$#';

            if (preg_match($regex, $path, $matches)) {
                array_shift($matches); // remove full match
                call_user_func_array($handler, $matches);
                return;
            }
        }

        http_response_code(404);
        echo '404 Not Found';
    }
}
