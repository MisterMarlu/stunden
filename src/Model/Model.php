<?php

namespace App\Model;

use App\Lib\App;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use JsonSerializable;

abstract class Model implements JsonSerializable
{

    protected ?int $id = null;

    public function __construct(array $arguments)
    {
        foreach ($arguments as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
                continue;
            }

            $parts = explode('_', $key);
            $newKey = lcfirst(str_replace(' ', '', ucwords(implode(' ', $parts))));

            if (property_exists($this, $newKey)) {
                $this->{$newKey} = $value;
            }
        }
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

    abstract public static function getTableName(): string;

    public static function find(int $id): ?static
    {
        $qb = App::instance()->getQueryBuilder();

        try {
            $result = $qb->select('*')
                ->from(static::getTableName())
                ->where($qb->expr()->eq('id', $qb->createNamedParameter($id)))
                ->executeQuery()
                ->fetchAssociative();
        } catch (Exception $e) {
            var_dump($e->getMessage());
            return null;
        }

        return new static($result);
    }

    public static function where(array $conditions): array
    {
        $qb = App::instance()->getQueryBuilder();

        $qb->select('*')
            ->from(static::getTableName());

        $where = self::parseConditions($conditions, $qb);

        try {
            $result = $qb->where(...$where)
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

    private static function parseConditions(array $conditions, QueryBuilder $qb): array
    {
        $where = [];

        foreach ($conditions as $condition) {
            if (
                !isset($condition[0], $condition[1])
                || empty($condition[0])
                || (empty($condition[1]) && (string)$condition[1] !== '0')
            ) {
                continue;
            }

            $column = $condition[0];
            $value = $condition[1];
            $operator = $condition[2] ?? '=';
            $operation = self::getOperationName($operator);

            if (!method_exists($qb->expr(), $operation)) {
                continue;
            }

            $where[] = $qb->expr()->{$operation}($column, $qb->createNamedParameter($value));
        }

        return $where;
    }

    private static function getOperationName(string $operator = '='): string
    {
        $mapping = [
            '<=' => 'lte',
            '<' => 'lt',
            '>' => 'mt',
            '>=' => 'mte',
            '=' => 'eq',
            '!=' => 'neq',
            '%' => 'like',
            'like' => 'like',
        ];

        if (!isset($mapping[$operator])) {
            $operator = '=';
        }

        return $mapping[$operator];
    }

    public static function findOrNew(array $data): static
    {
        $where = [];

        foreach ($data as $key => $value) {
            $where[] = [$key, $value];
        }

        $results = static::where($where);

        if (empty($results)) {
            return new static($data);
        }

        return $results[0];
    }

    private static function camelToSnake(string $input): string
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
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

    /**
     * @return void
     * @throws Exception
     */
    public function save(): void
    {
        if (!is_null($this->id)) {
            $this->update();
            return;
        }

        $this->insert();
    }

    /**
     * @throws Exception
     */
    private function update(): void
    {
        $qb = App::instance()->getQueryBuilder();
        $qb->update(static::getTableName());

        foreach (get_object_vars($this) as $column => $value) {
            $column = static::camelToSnake($column);
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
    private function insert(): void
    {
        $qb = App::instance()->getQueryBuilder();
        $qb->insert(static::getTableName());

        $values = get_object_vars($this);
        unset($values['id']);

        foreach ($values as $key => $value) {
            $column = static::camelToSnake($key);
            unset($values[$key]);
            $values[$column] = $qb->createNamedParameter($value);
        }

        $qb->values($values)
            ->executeStatement();
    }

    public function jsonSerialize(): array
    {
        $result = [];

        foreach (get_object_vars($this) as $key => $value) {
            $result[$key] = $value;
        }

        return $result;
    }
}