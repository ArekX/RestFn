<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace App\Data;


use ArekX\JsonQL\Data\Query;

class Role
{
    public function requestId(Query $query, array $fields)
    {

    }

    public function requestName(Query $query, array $fields)
    {

    }

    public function __invoke(Query $query, array $fields)
    {
        $this->requestId($query, $fields);
        $this->requestName($query, $fields);
    }
}