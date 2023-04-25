<?php

namespace App\Model;

abstract class MonthModel extends Model
{

    public static function findByMonth(?int $timeStamp = 0): array
    {
        if ($timeStamp === 0) {
            return static::all();
        }

        $month = (int)date('m', $timeStamp);
        $fromTime = mktime(0, 0, 0, $month, 1);
        $toTime = mktime(0, 0, 0, ($month + 1), 0);

        $conditions = [
            ['date', $fromTime, '>='],
            ['date', $toTime, '<='],
        ];

        return static::where($conditions);
    }
}