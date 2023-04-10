<?php
/**
 * @var int $month
 * @var \App\Model\Person[] $persons
 */
?>

<div>
    <form action="/month" method="post">
        <label for="month">Monat</label>
        <select id="month" name="month">
            <?php

            $fmt =  datefmt_create(
                'de_DE',
                IntlDateFormatter::FULL,
                0,
                'Europe/Berlin',
                IntlDateFormatter::GREGORIAN,
                'MMMM'
            );
            for ($i = 1; $i < 13; $i++) {
                $number = $i > 9 ? (string)$i : '0' . $i;
                $time = strtotime('01.' . $number . '.' . date('Y'));
                $monthName = datefmt_format($fmt, $time);
                $currentMonth = $i === (int)date('m');

                if (isset($_COOKIE['month'])) {
                    $currentMonth = $i === (int)$_COOKIE['month'];
                }

                if (isset($month)) {
                    $currentMonth = $i === (int)$month;
                }
                ?>
                <option value="<?php echo $i; ?>"
                        <?php if ($currentMonth) {
                            echo 'selected="selected"';
                        }
                        ?>
                ><?php echo $monthName; ?></option>
            <?php
            }
            ?>
        </select>
        <button type="submit">Auswählen</button>
    </form>
</div>

<div class="py-4">
    <div class="mb-2 flex flex-row">
        <div>
            <strong>Personen</strong>

            <ul id="persons">
                <?php
                foreach ($persons as $person) {
                    echo '<li data-id="' . $person->getId() . '">'.$person->getName().'</li>';
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

    <form method="post" action="/times">
        <div class="flex flex-row gap-2">
            <div>
                DATUM
                <input type="hidden" value="DATUM">
            </div>
            <div>
                <div>
                    <label for="name">Name</label>
                    <select name="name" id="name">

                    </select>
                </div>
                <div>
                    <label for="time-from">Von</label>
                    <input type="time" name="time-from" id="time-from">
                    <label for="time-to">Bis</label>
                    <input type="time" name="time-to" id="time-to">
                </div>
            </div>
            <div>
                Ergebnis von Zeitspanne in Stunden
            </div>
        </div>
        <div id="submit-list" class="flex flex-row gap-2">
            <div>
                <button type="submit">Speichern</button>
            </div>
        </div>
    </form>
</div>