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

<div>
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

<button type="button" id="print">Drucken</button>

<div class="py-4">
    <div class="mb-2 flex flex-row">
        <div>
            <strong>Personen</strong>

            <ul id="persons">
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

    <form method="post" action="/times" class="flex flex-col w-2/3">
        <?php
        $year = (int)date('Y');

        for ($d = 1; $d <= 31; $d++) {
            $time = mktime(12, 0, 0, $currentMonth, $d, $year);
            if ((int)date('m', $time) === $currentMonth) {
                $saveDate = date('Y-m-d', $time);
                $date = datefmt_format($fmtDate, $time);
                ?>
                <div class="flex flex-row justify-between align-middle gap-2 my-2 pb-2 border-b border-b-slate-900" data-row="<?php echo $d; ?>">
                    <div>
                        <?php echo $date; ?>
                        <input name="date-<?php echo $d; ?>" type="hidden" value="<?php echo $saveDate; ?>">
                    </div>
                    <div>
                        <div>
                            <label for="name-<?php echo $d; ?>">Name</label>
                            <select name="name-<?php echo $d; ?>" id="name-<?php echo $d; ?>">
                            </select>
                        </div>
                        <div>
                            <label for="time-from-<?php echo $d; ?>">Von</label>
                            <input type="time" name="time-from-<?php echo $d; ?>" id="time-from-<?php echo $d; ?>">
                            <label for="time-to-<?php echo $d; ?>">Bis</label>
                            <input type="time" name="time-to-<?php echo $d; ?>" id="time-to-<?php echo $d; ?>">
                        </div>
                    </div>
                    <div data-result="<?php echo $d; ?>">
                        0
                    </div>


                    <div>
                        <div>
                            <label for="name-<?php echo $d; ?>">Name</label>
                            <select name="name-<?php echo $d; ?>" id="name-<?php echo $d; ?>">
                            </select>
                        </div>
                        <div>
                            <label for="time-from-<?php echo $d; ?>">Von</label>
                            <input type="time" name="time-from-<?php echo $d; ?>" id="time-from-<?php echo $d; ?>">
                            <label for="time-to-<?php echo $d; ?>">Bis</label>
                            <input type="time" name="time-to-<?php echo $d; ?>" id="time-to-<?php echo $d; ?>">
                        </div>
                    </div>
                    <div data-result="<?php echo $d; ?>">
                        0
                    </div>


                    <div>
                        <div>
                            <label for="name-<?php echo $d; ?>">Name</label>
                            <select name="name-<?php echo $d; ?>" id="name-<?php echo $d; ?>">
                            </select>
                        </div>
                        <div>
                            <label for="time-from-<?php echo $d; ?>">Von</label>
                            <input type="time" name="time-from-<?php echo $d; ?>" id="time-from-<?php echo $d; ?>">
                            <label for="time-to-<?php echo $d; ?>">Bis</label>
                            <input type="time" name="time-to-<?php echo $d; ?>" id="time-to-<?php echo $d; ?>">
                        </div>
                    </div>
                    <div data-result="<?php echo $d; ?>">
                        0
                    </div>
                </div>
        <?php
            }
        }
        ?>
        <div id="submit-list" class="flex flex-row gap-2">
            <div>
                <button type="submit">Speichern</button>
            </div>
        </div>
    </form>
</div>