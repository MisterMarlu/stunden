<?php

namespace App\Controller;

use App\Model\Person;
use Flight;

class HomeController extends Controller
{
    public function index(): void
    {
        $arguments = [];

        if (isset($_COOKIE['month'])) {
            $arguments['month'] = (int)$_COOKIE['month'];
        }

        $arguments['persons'] = Person::all();

        $this->view('index', $arguments);
    }

    public function month(): void
    {
        setcookie('month', (int)$_POST['month'], time() + (7 * 24 * 60 * 60));
        Flight::redirect('/');
    }

    public function times(): void
    {
        $this->view('index');
    }
}