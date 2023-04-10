<?php

namespace App\Lib;

use Flight;

class App
{

    private string $basePath;

    protected static ?App $instance = null;

    protected function __construct(string $basePath)
    {
        $this->basePath = $basePath;
        static::$instance = $this;
    }

    public function run(): void
    {
        require_once $this->basePath . '/routes/web.php';
        Flight::start();
    }

    public function basePath(string $path = ''): string
    {
        if (!empty($path) && !str_starts_with($path, '/')) {
            $path = '/' . $path;
        }

        return $this->basePath . $path;
    }

    public static function instance(string $basePath = ''): App
    {
        if (!static::$instance instanceof App) {
            new self($basePath);
        }

        return static::$instance;
    }
}