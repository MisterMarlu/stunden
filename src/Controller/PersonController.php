<?php

namespace App\Controller;

use App\Model\Person;
use Flight;

class PersonController extends Controller
{
    public function add(): void
    {
        $person = new Person($this->getJsonPost());
        $person->save();
        Flight::json([]);
    }
}