<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Values;

use ArekX\JsonQL\Types\DataProviderType;

class DataProvider extends TypedValue
{
    public static function create($data)
    {
        return new static($data, DataProviderType::class);
    }

    public static function definition(): array
    {
        return DataProviderType::strictValidator()->getDefinition();
    }
}