<?php

namespace App\Lib;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use Flight;

class App
{

    private string $basePath;

    protected static ?App $instance = null;

    protected Connection $connection;

    /**
     * @throws Exception
     */
    protected function __construct(string $basePath)
    {
        $this->basePath = $basePath;
        $connectionParams = require_once $this->basePath('/conn.php');
        $this->connection = DriverManager::getConnection($connectionParams);
        $this->connection->connect();
        $this->checkTables();

        static::$instance = $this;
    }

    protected function checkTables(): void
    {
        $sm = $this->connection->createSchemaManager();
        if (!empty($sm->listTables())) {
            return;
        }

        $sql = file_get_contents($this->basePath('/tables.sql'));
        $this->connection->executeQuery($sql);
    }

    public function getQueryBuilder(): QueryBuilder
    {
        return $this->connection->createQueryBuilder();
    }

    public function run(): void
    {
        require_once $this->basePath . '/routes/web.php';
        Flight::start();
    }

    public function basePath(string $path = ''): string
    {
        if (!empty($path) && !str_starts_with($path, '/')) {
            $path = '/' . $path;
        }

        return $this->basePath . $path;
    }

    public static function instance(string $basePath = ''): App
    {
        if (!static::$instance instanceof App) {
            new self($basePath);
        }

        return static::$instance;
    }

    protected function connectDB(): void
    {

    }
}