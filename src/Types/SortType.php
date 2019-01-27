<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Types;

use ArekX\JsonQL\Validation\Fields\ObjectField;

use function ArekX\JsonQL\Validation\arrayType;
use function ArekX\JsonQL\Validation\boolType;
use function ArekX\JsonQL\Validation\enumType;
use function ArekX\JsonQL\Validation\objectType;


class SortType extends BaseType
{
    protected static function typeFields(): array
    {
        return [
            'sorted_by' => self::sortedByType(),
            'allow_multisort' => boolType()->required()->info('Whether or not multisort is allowed.'),
            'sort_items' => self::sortItemsType()
        ];
    }

    protected static function sortedByType()
    {
        return objectType()->of([
            ObjectField::ANY_KEY => enumType(['ascending', 'descending'])
        ])->required()->info('Values that were used for sorting.');
    }

    protected static function sortItemsType()
    {
        return objectType()->of([
            ObjectField::ANY_KEY => arrayType()->required()->of(enumType(['ascending', 'descending']))
        ])->required()->info('Items which are available for sorting by.');
    }
}