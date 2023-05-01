<?php
/**
 * @var string $month
 * @var Person[] $persons
 * @var Shift[] $shifts
 * @var Vacation[] $vacations
 */

use App\Model\Person;
use App\Model\Shift;
use App\Model\Vacation;

$fmt = datefmt_create(
    'de_DE',
    IntlDateFormatter::FULL,
    0,
    'Europe/Berlin',
    IntlDateFormatter::GREGORIAN,
    'MMMM'
);
$fmtDate = datefmt_create(
    'de_DE',
    IntlDateFormatter::FULL,
    0,
    'Europe/Berlin',
    IntlDateFormatter::GREGORIAN,
    'dd.MM.YYYY - EEEE'
);

$currentMonth = (int)date('m');

if (isset($_COOKIE['month'])) {
    $currentMonth = (int)$_COOKIE['month'];
}

if (isset($month)) {
    $currentMonth = (int)$month;
}
?>

<div class="print:hidden">
    <form action="/month" method="post">
        <label for="month">Monat</label>
        <select id="month" name="month">
            <?php
            for ($i = 1; $i < 13; $i++) {
                $number = $i > 9 ? (string)$i : '0' . $i;
                $time = strtotime('01.' . $number . '.' . date('Y'));
                $monthName = datefmt_format($fmt, $time);
                $isCurrentMonth = $i === $currentMonth;
                ?>
                <option value="<?php
                echo $i; ?>"
                    <?php
                    if ($isCurrentMonth) {
                        echo 'selected="selected"';
                    }
                    ?>
                ><?php
                    echo $monthName; ?></option>
                <?php
            }
            ?>
        </select>
        <button type="submit">Auswählen</button>
    </form>
</div>

<button type="button" id="print" class="print:hidden">Drucken</button>

<div class="py-4">
    <div class="mb-2 flex flex-row gap-4 print:hidden print:opacity-0">
        <div>
            <strong>Personen</strong>

            <ul data-persons>
                <li data-id="">---</li>
                <?php
                foreach ($persons as $person) {
                    echo '<li data-id="' . $person->getId() . '" data-color="' . $person->getColor() . '">' . $person->getName() . '</li>';
                }
                ?>
            </ul>
        </div>

        <form class="flex flex-col gap-4" data-person-form>
            <div class="flex flex-row gap-2">
                <label for="person-name">Neue Person</label>
                <input id="person-name" data-person-name>
            </div>
            <div class="flex flex-row gap-2">
                <label for="person-color">Farbe</label>
                <input id="person-color" type="color" value="#ffffff" data-person-color>
            </div>
            <button type="submit" class="border border-b-slate-900">Person hinzufügen</button>
        </form>
    </div>

    <form method="post" action="/times" class="flex flex-col w-full">
        <?php
        $year = (int)date('Y');
        $columns = 5;

        for ($d = 1; $d <= 31; $d++) {
            $time = mktime(0, 0, 0, $currentMonth, $d, $year);
            if ((int)date('m', $time) === $currentMonth) {
                $date = datefmt_format($fmtDate, $time);
                $vacation = null;

                foreach ($vacations as $vac) {
                    if (date('Y-m-d', $time) === date('Y-m-d', $vac->getDate())) {
                        $vacation = $vac;
                    }
                }
                ?>
                <div class="flex flex-row justify-between align-middle gap-2 my-2 pb-2 border-b border-b-slate-900"
                     data-row="<?php
                     echo $d; ?>">
                    <div class="flex flex-row items-center">
                        <?php
                        echo $date; ?>
                        <input name="dates[<?php
                        echo $d; ?>][date]" type="hidden" value="<?php
                        echo $time; ?>">
                    </div>

                    <?php
                    for ($i = 0; $i < $columns; $i++) {
                        $shift = null;
                        $id = $d . '-' . $i;

                        foreach ($shifts as $tmpShift) {
                            if (date('Y-m-d', $time) === date('Y-m-d', $tmpShift->getDate())) {
                                if ($tmpShift->getShiftIndex() === $i) {
                                    $shift = $tmpShift;
                                }
                            }
                        }
                        ?>
                        <div class="flex flex-row gap-4 items-center" data-shift="<?php echo $id; ?>">
                            <div class="flex flex-col w-full gap-2">
                                <div class="flex flex-row gap-2">
                                    <label class="print:hidden" for="name-<?php
                                    echo $id; ?>">Name</label>
                                    <select name="dates[<?php
                                    echo $d; ?>][<?php
                                    echo $i; ?>][name]"
                                            data-name="<?php echo $id; ?>"
                                            data-value="<?php echo $shift?->getId(); ?>"
                                            class="appearance-none p-1"
                                            id="name-<?php
                                            echo $id; ?>">
                                        <option>---</option>

                                        <?php
                                        foreach ($persons as $person) {
                                            echo '<option';
                                            echo ' value="' . $person->getId() . '"';
                                            echo ' data-color="' . $person->getColor() . '"';

                                            if ($shift?->getPersonId() === $person->getId()) {
                                                echo ' selected="selected"';
                                            }

                                            echo '>' . $person->getName() . '</option>';
                                        }
                                        ?>

                                    </select>
                                    <span class="hidden print:block"><strong data-name-print="<?php echo $id; ?>"></strong></span>
                                </div>

                                <div class="flex flex-row w-full gap-2 justify-between">
                                    <div class="flex flex-row gap-2">
                                        <label class="print:hidden" for="time-from-<?php
                                        echo $id; ?>">Von</label>
                                        <input type="time"
                                               name="dates[<?php
                                        echo $d; ?>][<?php
                                        echo $i; ?>][from]"
                                               data-from="<?php echo $id; ?>"

                                               <?php
                                               if ($shift instanceof Shift) {
                                                   echo 'value="' . $shift->getFromTime() . '"';
                                               }
                                               ?>

                                               id="time-from-<?php
                                        echo $id; ?>">
                                    </div>
                                    <span class="hidden print:block">-</span>
                                    <div class="flex flex-row gap-2">
                                        <label class="print:hidden" for="time-to-<?php
                                        echo $id; ?>">Bis</label>
                                        <input type="time"
                                               name="dates[<?php
                                        echo $d; ?>][<?php
                                        echo $i; ?>][to]"
                                               data-to="<?php echo $id; ?>"

                                               <?php
                                               if ($shift instanceof Shift) {
                                                   echo 'value="' . $shift->getToTime() . '"';
                                               }
                                               ?>

                                               id="time-to-<?php
                                        echo $id; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-row gap-1">
                                <span data-result="<?php
                                echo $id; ?>">00:00</span>
                            </div>
                        </div>
                        <?php
                    }
                    ?>

                    <div class="flex flex-row gap-4 items-center">
                        <div class="flex flex-col w-full gap-2">
                            <div class="flex flex-row gap-2">
                                <label for="vac-<?php
                                echo $d; ?>">Bemerkung</label>
                            </div>
                            <div class="flex flex-row w-full gap-2 justify-between">
                                <div class="flex flex-row gap-2">
                                    <textarea cols="30"
                                              rows="3"
                                        name="dates[<?php
                                    echo $d; ?>][vac]"
                                           id="vac-<?php
                                    echo $d; ?>"><?php echo $vacation?->getPersons(); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <?php
            }
        }
        ?>

        <div class="flex flex-col justify-between align-middle gap-2 my-2 pb-2 border-b border-b-slate-900"
             data-month-result>
            <?php
            foreach ($persons as $person) {?>
                <div>
                    <span style="background-color: <?php echo $person->getColor(); ?>;" class="rounded p-1"><?php echo $person->getName(); ?></span>
                    <span data-id="<?php echo $person->getId(); ?>">00:00</span>
                </div>
            <?php
            }
            ?>
        </div>
        <div id="submit-list" class="flex flex-row gap-2 print:hidden">
            <div>
                <button type="submit">Speichern</button>
            </div>
        </div>
    </form>
</div>
