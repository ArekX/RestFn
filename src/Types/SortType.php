<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Types;

use function ArekX\JsonQL\Validation\arrayType;
use function ArekX\JsonQL\Validation\boolType;
use function ArekX\JsonQL\Validation\enumType;
use function ArekX\JsonQL\Validation\objectType;
use ArekX\JsonQL\Validation\Fields\ObjectField;

class SortType extends BaseType
{
    public static function fields(): array
    {
        $enumType = enumType(['ascending', 'descending']);

        return [
            'sorted_by' => objectType()->of([
                ObjectField::ANY_KEY => $enumType
            ])->required()->info('Values that were used for sorting.'),
            'allow_multisort' => boolType()->required()->info('Whether or not multisort is allowed.'),
            'sort_items' => objectType()->of([
                ObjectField::ANY_KEY => arrayType()->required()->of($enumType)
            ])->required()->info('Items which are available for sorting by.')
        ];
    }
}