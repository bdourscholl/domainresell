<?php

declare(strict_types=1);

namespace App\Core;

use App\Middleware\AuthMiddleware;
use App\Middleware\AdminMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Middleware\LocaleMiddleware;
use App\Middleware\MaintenanceMiddleware;
use App\Middleware\VerificationMiddleware;

class Router
{
    private array $routes = [];
    private array $middlewareMap = [
        'auth' => AuthMiddleware::class,
        'admin' => AdminMiddleware::class,
        'csrf' => CsrfMiddleware::class,
        'locale' => LocaleMiddleware::class,
        'maintenance' => MaintenanceMiddleware::class,
        'verified' => VerificationMiddleware::class,
    ];

    public function get(string $path, string $action, array $middleware = []): void
    {
        $this->addRoute('GET', $path, $action, $middleware);
    }

    public function post(string $path, string $action, array $middleware = []): void
    {
        $this->addRoute('POST', $path, $action, $middleware);
    }

    private function addRoute(string $method, string $path, string $action, array $middleware): void
    {
        $pattern = preg_replace('/\{([a-zA-Z]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'path' => $path,
            'action' => $action,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(Request $request): Response
    {
        $method = $request->getMethod();
        $uri = $request->getUri();

        // Always apply locale middleware
        $localeMiddleware = new LocaleMiddleware();
        $localeMiddleware->handle($request);

        // Check maintenance mode
        $maintenanceMiddleware = new MaintenanceMiddleware();
        $maintenanceResponse = $maintenanceMiddleware->handle($request);
        if ($maintenanceResponse !== null) {
            return $maintenanceResponse;
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $request->setParams($params);

                // Run middleware
                foreach ($route['middleware'] as $mw) {
                    if (isset($this->middlewareMap[$mw])) {
                        $middlewareClass = $this->middlewareMap[$mw];
                        $middleware = new $middlewareClass();
                        $result = $middleware->handle($request);
                        if ($result instanceof Response) {
                            return $result;
                        }
                    }
                }

                // CSRF check for POST (except webhooks)
                if ($method === 'POST' && !str_starts_with($uri, '/webhook/')) {
                    $csrf = new CsrfMiddleware();
                    $result = $csrf->handle($request);
                    if ($result instanceof Response) {
                        return $result;
                    }
                }

                return $this->callAction($route['action'], $request);
            }
        }

        return Response::html(View::render('errors/404'), 404);
    }

    private function callAction(string $action, Request $request): Response
    {
        [$controllerName, $method] = explode('@', $action);

        $controllerClass = 'App\\Controllers\\' . $controllerName;

        if (!class_exists($controllerClass)) {
            return Response::html('Controller not found: ' . $controllerName, 500);
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            return Response::html('Method not found: ' . $method, 500);
        }

        $result = $controller->$method($request);

        if ($result instanceof Response) {
            return $result;
        }

        return Response::html((string) $result);
    }
}
