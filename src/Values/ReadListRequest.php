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
    protected static $type = ReadListRequestType::class;
}