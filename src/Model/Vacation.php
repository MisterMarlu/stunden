<?php

namespace App\Model;

class Vacation extends MonthModel
{
    public static function getTableName(): string
    {
        return 'vacation';
    }

    protected int $date;

    protected string $persons;

    /**
     * @return int
     */
    public function getDate(): int
    {
        return $this->date;
    }

    /**
     * @param int $date
     */
    public function setDate(int $date): void
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getPersons(): string
    {
        return $this->persons;
    }

    /**
     * @param string $persons
     */
    public function setPersons(string $persons): void
    {
        $this->persons = $persons;
    }
}