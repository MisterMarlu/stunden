<?php

namespace App\Controller;

use App\Lib\App;
use Flight;
use flight\net\Request;

abstract class Controller
{
    protected App $app;

    public function __construct()
    {
        $this->app = App::instance();
    }

    protected function view(string $name, array $arguments = []): void
    {
        $path = implode('/', explode('.', $name)) . '.php';

        Flight::render($path, $arguments, 'body_content');
        Flight::render('layout');
    }

    protected function getJsonPost(): array
    {
        return json_decode($_POST['json'], true);
    }

    protected function getRequest(): Request
    {
        return Flight::request();
    }
}