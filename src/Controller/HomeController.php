<?php

namespace App\Controller;

use App\Model\Person;
use App\Model\Shift;
use App\Model\Vacation;
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
        $arguments['shifts'] = Shift::findByMonth($arguments['month'] ?? null);
        $arguments['vacations'] = Vacation::findByMonth($arguments['month'] ?? null);

        $this->view('index', $arguments);
    }

    public function month(): void
    {
        setcookie('month', (int)$_POST['month'], time() + (7 * 24 * 60 * 60));
        Flight::redirect('/');
    }

    protected static array $dayKeys = [
        'date',
        'vac',
    ];

    public function times(): void
    {
        $days = $_POST['dates'];

        foreach ($days as $day) {
            $date = (int)$day['date'];
            $vac = trim($day['vac']);

            if (!empty($vac)) {
                $vacData = [
                    'date' => $date,
                ];
                $vacation = Vacation::findOrNew($vacData);
                $vacation->setPersons($vac);
                $vacation->save();
            }

            foreach ($day as $index => $shiftData) {
                if (in_array($index, static::$dayKeys) || (int)$shiftData['name'] === 0) {
                    continue;
                }

                $data = [
                    'date' => $date,
                    'shift_index' => $index,
                ];
                $shift = Shift::findOrNew($data);
                $shift->setFromTime($shiftData['from']);
                $shift->setToTime($shiftData['to']);
                $shift->setPersonId((int)$shiftData['name']);
                $shift->save();
            }
        }

        Flight::redirect('/');
    }
}