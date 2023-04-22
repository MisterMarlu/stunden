<?php

namespace App\Model;

class Shift extends Model
{
    public static function getTableName(): string
    {
        return 'shift';
    }

    protected string $fromTime;

    protected string $toTime;

    protected int $personId;

    /**
     * @return string
     */
    public function getFromTime(): string
    {
        return $this->fromTime;
    }

    /**
     * @param string $fromTime
     */
    public function setFromTime(string $fromTime): void
    {
        $this->fromTime = $fromTime;
    }

    /**
     * @return string
     */
    public function getToTime(): string
    {
        return $this->toTime;
    }

    /**
     * @param string $toTime
     */
    public function setToTime(string $toTime): void
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