<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Types;

use function ArekX\JsonQL\Validation\objectField;

class ReadOneRequestType extends BaseType
{
    protected static function typeFields(): array
    {
        return [
            'at' => static::atType()
        ];
    }

    protected static function atType()
    {
        return objectField()->required();
    }
}