<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace App\Services;

use App\Data\Select;

class Database
{
    public $pdo;

    public function __construct($setup)
    {
        $this->pdo = new \PDO($setup['dsn'], $setup['username'], $setup['password']);
    }

    public function get(Select $select)
    {
        [$sql, $params] = $select->toSql();

        $statement = $this->pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $statement->bindValue($key, $value);
        }

        if (!$statement->execute()) {
            throw new \Exception('Failed: ' . implode(",", $statement->errorInfo()) . '| sql:' . $sql);
        };
        return $statement->fetchAll();
    }
}