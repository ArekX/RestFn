<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Types;

use function ArekX\JsonQL\Validation\anyType;
use function ArekX\JsonQL\Validation\enumType;
use function ArekX\JsonQL\Validation\numberType;
use function ArekX\JsonQL\Validation\objectType;
use ArekX\JsonQL\Validation\Fields\ObjectField;

class ReadListRequestType extends BaseType
{
    public static function fields(): array
    {
        return [
            'at' => objectType()->of([ObjectField::ANY_KEY => anyType()])->required(),
            'pagination' => objectType()->of([
                'page' => numberType(),
                'size' => numberType()->min(10)->max(50)
            ]),
            'sort' => objectType()->of([
                ObjectField::ANY_KEY => enumType(['ascending', 'descending'])
            ])
        ];
    }
}