<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Data\Scopes;


use ArekX\JsonQL\Data\Query;

interface ScopeInterface
{
    public function apply(Query $query, array $subFields);
    public static function from($subItemClass, array $setup);
}