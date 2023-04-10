<?php

$local = [
    'dbname' => 'db',
    'user' => 'db',
    'password' => 'db',
    'host' => 'db',
    'driver' => 'pdo_mysql',
];

$prod = [
    'dbname' => 'mydb',
    'user' => 'user',
    'password' => 'secret',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
];

if (getenv('IS_DDEV_PROJECT') !== false) {
    return $local;
}

return $prod;