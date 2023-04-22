<?php
/**
 * @var string $month
 * @var \App\Model\Person[] $persons
 */

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
    <div class="mb-2 flex flex-row print:hidden">
        <div>
            <strong>Personen</strong>

            <ul id="persons">
                <li data-id="">---</li>
                <?php
                foreach ($persons as $person) {
                    echo '<li data-id="' . $person->getId() . '">' . $person->getName() . '</li>';
                }
                ?>
            </ul>
        </div>

        <form class="flex flex-col" id="person-form">
            <label for="person-name">Neue Person</label>
            <input id="person-name">
            <button type="submit">Person hinzufügen</button>
        </form>
    </div>

    <form method="post" action="/times" class="flex flex-col w-full">
        <?php
        $year = (int)date('Y');
        $columns = 3;

        for ($d = 1; $d <= 31; $d++) {
            $time = mktime(0, 0, 0, $currentMonth, $d, $year);
            if ((int)date('m', $time) === $currentMonth) {
                $date = datefmt_format($fmtDate, $time);
                ?>
                <div class="flex flex-row justify-between align-middle gap-2 my-2 pb-2 border-b border-b-slate-900" data-row="<?php echo $d; ?>">
                    <div>
                        <?php echo $date; ?>
                        <input name="dates[<?php echo $d; ?>][date]" type="hidden" value="<?php echo $time; ?>">
                    </div>

                    <?php
                    for ($i = 0; $i < $columns; $i++) {
                        ?>
                    <div class="flex flex-row gap-4 items-center">
                        <div class="flex flex-col w-full gap-2">
                            <div class="flex flex-row gap-2">
                                <label class="print:hidden" for="name-<?php echo $d; ?>-<?php echo $i; ?>">Name</label>
                                <select name="dates[<?php echo $d; ?>][<?php echo $i; ?>][name]"
                                        class="appearance-none"
                                        id="name-<?php echo $d; ?>-<?php echo $i; ?>">
                                </select>
                                <span class="hidden print:block"><strong data-name="name-<?php echo $d; ?>-<?php echo $i; ?>"></strong></span>
                            </div>
                            <div class="flex flex-row w-full gap-2 justify-between">
                                <div class="flex flex-row gap-2">
                                    <label class="print:hidden" for="time-from-<?php echo $d; ?>-<?php echo $i; ?>">Von</label>
                                    <input type="time" name="dates[<?php echo $d; ?>][<?php echo $i; ?>][from]" id="time-from-<?php echo $d; ?>-<?php echo $i; ?>">
                                </div>
                                <span class="hidden print:block">-</span>
                                <div class="flex flex-row gap-2">
                                    <label class="print:hidden" for="time-to-<?php echo $d; ?>-<?php echo $i; ?>">Bis</label>
                                    <input type="time" name="dates[<?php echo $d; ?>][<?php echo $i; ?>][to]" id="time-to-<?php echo $d; ?>-<?php echo $i; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-row gap-1">
                            <span data-result="<?php echo $d; ?>-<?php echo $i; ?>">0</span>
                            <span>Stunden</span>
                        </div>
                    </div>
                    <?php
                    }
                    ?>

                    <div class="flex flex-row gap-4 items-center">
                        <div class="flex flex-col w-full gap-2">
                            <div class="flex flex-row gap-2">
                                <label for="vac-<?php echo $d; ?>">Urlaub</label>
                            </div>
                            <div class="flex flex-row w-full gap-2 justify-between">
                                <div class="flex flex-row gap-2">
                                    <input name="dates[<?php echo $d; ?>][vac]" id="vac-<?php echo $d; ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
        <?php
            }
        }
        ?>

        <div class="flex flex-col justify-between align-middle gap-2 my-2 pb-2 border-b border-b-slate-900">
            <?php
            foreach ($persons as $person) {
                echo '<div data-id="' . $person->getId() . '">' . $person->getName() . ': <span data-id="hours-' . $person->getId() . '"></span></div>';
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