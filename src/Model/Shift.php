<?php

namespace App\Model;

/**
 *
 */
class Shift extends MonthModel
{
    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'shift';
    }

    /**
     * @var string
     */
    protected string $fromTime;

    /**
     * @var string
     */
    protected string $toTime;

    /**
     * @var int
     */
    protected int $date;

    /**
     * @var int
     */
    protected int $shiftIndex;

    /**
     * @var int
     */
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
     * @return int
     */
    public function getShiftIndex(): int
    {
        return $this->shiftIndex;
    }

    /**
     * @param int $shiftIndex
     */
    public function setShiftIndex(int $shiftIndex): void
    {
        $this->shiftIndex = $shiftIndex;
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

    /**
     * @return Person|null
     */
    public function getPerson(): ?Person
    {
        return Person::find($this->personId);
    }
}