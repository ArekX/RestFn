<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Types;

use function ArekX\JsonQL\Validation\anyType;
use ArekX\JsonQL\Validation\Fields\ObjectField;
use function ArekX\JsonQL\Validation\objectType;

class ReadOneRequestType extends BaseType
{
    public static function fields(): array
    {
        return [
            'at' => objectType()->of([
                ObjectField::ANY_KEY => anyType()
            ])->required()
        ];
    }
}