<?php

namespace App\Model;

class Shift extends Model
{
    public static function getTableName(): string
    {
        return 'shift';
    }

    protected int $fromTime;

    protected int $toTime;

    protected int $personId;

    /**
     * @return int
     */
    public function getFromTime(): int
    {
        return $this->fromTime;
    }

    /**
     * @param int $fromTime
     */
    public function setFromTime(int $fromTime): void
    {
        $this->fromTime = $fromTime;
    }

    /**
     * @return int
     */
    public function getToTime(): int
    {
        return $this->toTime;
    }

    /**
     * @param int $toTime
     */
    public function setToTime(int $toTime): void
    {
        $this->toTime = $toTime;
    }

    /**
     * @return int
     */
    public function getPersonId(): int
    {
        return $this->personId;
    }

    /**
     * @param int $personId
     */
    public function setPersonId(int $personId): void
    {
        $this->personId = $personId;
    }

    public function getPerson(): ?Person
    {
        return Person::find($this->personId);
    }
}