<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Values;

use ArekX\JsonQL\Types\ReadListRequestType;

class ReadListRequest extends TypedValue
{
    public static function create($data)
    {
        return new static($data, ReadListRequestType::class);
    }

    public static function definition(): array
    {
        return ReadListRequestType::strictValidator()->getDefinition();
    }
}