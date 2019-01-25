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
    protected static $type = ReadOneRequestType::class;
}