<?php

namespace App\Model;

use App\Lib\App;
use Doctrine\DBAL\Exception;

abstract class Model
{

    protected ?int $id = null;

    public function __construct(array $arguments)
    {
        foreach ($arguments as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public static function all(): array
    {
        $qb = App::instance()->getQueryBuilder();

        try {
            $result = $qb->select('*')
                ->from(static::getTableName())
                ->executeQuery()
                ->fetchAllAssociative();
        } catch (Exception $e) {
            var_dump($e->getMessage());
            return [];
        }

        $models = [];

        foreach ($result as $item) {
            $models[] = new static($item);
        }

        return $models;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function save(): void {
        if (!is_null($this->id)) {
            $this->update();
            return;
        }

        $this->insert();
    }

    /**
     * @throws Exception
     */
    private function update(): void {
        $qb = App::instance()->getQueryBuilder();
        $qb->update(static::getTableName());

        foreach (get_object_vars($this) as $column => $value) {
            if ($column === 'id') {
                continue;
            }

            $qb->set($column, $qb->createNamedParameter($value));
        }

        $qb->where($qb->expr()->eq('id', $this->id))
            ->executeStatement();
    }

    /**
     * @return void
     * @throws Exception
     */
    private function insert(): void {
        $qb = App::instance()->getQueryBuilder();
        $qb->insert(static::getTableName());

        $values = get_object_vars($this);
        unset($values['id']);
        $values = array_map(function (mixed $value) use ($qb) {
            return $qb->createNamedParameter($value);
        }, $values);
        $qb->values($values)
            ->executeStatement();
    }

    abstract public static function getTableName(): string;
}