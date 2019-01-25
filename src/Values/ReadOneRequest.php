<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Values;

use ArekX\JsonQL\Types\ReadOneRequestType;

class ReadOneRequest extends TypedValue
{
    public static function create($data)
    {
        return new static($data, ReadOneRequestType::class);
    }

    public static function definition(): array
    {
        return ReadOneRequestType::strictValidator()->getDefinition();
    }
}