<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace App\Data;


use ArekX\JsonQL\Data\Query;

class User
{
    public function resolveId(Query $query)
    {

    }

    public function resolveName(Query $query)
    {

    }

    public function resolveRole(Query $query, $value, array $fields)
    {
        $query->requestField('role', new Role($value));
    }
}