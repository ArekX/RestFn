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
    protected static $type = DataProviderType::class;

    protected static function defaultValues()
    {
        return [
            'items' => ['s']
        ];
    }
}