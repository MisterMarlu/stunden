<?php

namespace App\Model;

class Person extends Model
{
    public static function getTableName(): string
    {
        return 'person';
    }

    protected string $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}