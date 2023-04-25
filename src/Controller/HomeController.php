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
                    'persons' => $vac,
                ];
                $vacation = Vacation::findOrNew($vacData);
                $vacation->save();
            }

            foreach ($day as $index => $shiftData) {
                if (in_array($index, static::$dayKeys) || empty(trim($shiftData['name']))) {
                    continue;
                }

                $data = [
                    'from_time' => (int)$shiftData['from'],
                    'to_time' => (int)$shiftData['to'],
                    'date' => $date,
                    'shift_index' => $index,
                    'person_id' => (int)$shiftData['name'],
                ];
                $shift = Shift::findOrNew($data);
                $shift->save();
            }
        }

        Flight::redirect('/');
    }
}