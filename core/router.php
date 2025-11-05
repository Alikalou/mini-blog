<?php

class Router
{
    protected array $routes = [
        'GET'  => [],
        'POST' => [],
    ];

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

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH); // strip ?query

        if (!isset($this->routes[$method])) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        foreach ($this->routes[$method] as $route) {
            $pattern = $route['pattern']; // e.g. '/posts/show/{id}'
            $handler = $route['handler'];

            // Convert '/posts/show/{id}' → '#^/posts/show/([^/]+)$#'
            $regex = '#^' . preg_replace('#\{[^/]+\}#', '([^/]+)', $pattern) . '$#';

            if (preg_match($regex, $path, $matches)) {
                array_shift($matches); // remove full match
                // $matches now contains ['1'] or ['2', 'comments', ...] etc.
                call_user_func_array($handler, $matches);
                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
