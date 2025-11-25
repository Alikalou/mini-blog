<?php

require_once __DIR__.'/flash.php';
require_once __DIR__.'/auth.php';

class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler, ?string $guard = null): void
    {
        $this->routes['GET'][] = [
            'pattern' => $path,
            'handler' => $handler,
            'guard' => $guard,
        ];
    }

    public function post(string $path, callable $handler, ?string $guard = null): void
    {
        $this->routes['POST'][] = [
            'pattern' => $path,
            'handler' => $handler,
            'guard' => $guard,
        ];
    }

    public function dispatch(string $method, string $path): void
    {
        $path = parse_url($path, PHP_URL_PATH) ?? '/';

        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $route) {
                    
            $pattern = $route['pattern'];
            $handler = $route['handler'];
            $guard= $route['guard']?? null;
            
            // Convert '/posts/delete/{id}' → '#^/posts/delete/([^/]+)$#'
            $regex = '#^' . preg_replace('#\{[^/]+\}#', '([^/]+)', $pattern) . '$#';

            if (preg_match($regex, $path, $matches)) {
                    if($guard=== 'auth' && !Auth::check())
                    {
                        Flash::setFlash('warning', 'You must be logged in to do that.');
                        header('Location: /login');
                        exit;
                    }
                    array_shift($matches); // remove full match
                    call_user_func_array($handler, $matches);
                    return;
             
            }
        }

        // If no route matched
        Flash::setFlash('error', '404 Not Found');
        header('Location: /');
        exit;
    }
}
