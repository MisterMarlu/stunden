<?php

namespace App\Controller;

use App\Model\Person;
use Flight;

class PersonController extends Controller
{
    public function add(): void
    {
        $name = $this->getJsonPost()['name'];
        $person = new Person($this->getJsonPost());
        $person->save();

        $persons = Person::where([['name', $name]]);

        Flight::json($persons[0] ?? []);
    }
}