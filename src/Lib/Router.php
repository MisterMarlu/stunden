<?php

namespace App\Lib;

use Flight;

class Router
{
    public static function add(string $uri, callable|array|string $callback): void
    {
        if (is_string($callback) && str_contains($callback, '@')) {
            $callback = explode('@', $callback);
            $method = array_pop($callback);
            $class = array_pop($callback);
            $class = '\\App\\Controller\\' . $class;
            $controller = new $class();

            $callback = [
                $controller,
                $method,
            ];
        }

        Flight::route($uri, $callback);
    }
}