<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

use ArekX\JsonQL\Config\Config;
use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Rest\Handlers\Reader;

return [
    Config::SERVICES => [
        Reader::class => [
            'namespace' => 'App\Readers'
        ],
        \App\Services\Database::class => [
            'dsn' => 'mysql:host=localhost;dbname=sakila',
            'username' => 'test',
            'password' => 'test',
            'options' => [
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            ]
        ]
    ]
];