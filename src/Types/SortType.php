<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Types;

use function ArekX\JsonQL\Validation\anyType;
use function ArekX\JsonQL\Validation\arrayType;
use function ArekX\JsonQL\Validation\boolType;
use function ArekX\JsonQL\Validation\enumType;
use function ArekX\JsonQL\Validation\nullType;
use function ArekX\JsonQL\Validation\numberType;
use function ArekX\JsonQL\Validation\objectType;
use function ArekX\JsonQL\Validation\orType;
use function ArekX\JsonQL\Validation\stringType;
use function ArekX\JsonQL\Validation\subType;
use function DI\string;

class SortType extends BaseType
{
    public static function fields(): array
    {
        $sortType = objectType([
            '[key]' => arrayType()->required()->of(enumType(['ascending', 'descending']))
        ])->required();

        return [
            'sorted_by' => arrayType()->required()->of($sortType)->info('Values that were used for sorting.'),
            'allow_multisort' => boolType()->required()->info('Whether or not multisort is allowed.'),
            'sort_items' => arrayType()->required()->of($sortType)->info('Items which are available for sorting by.')
        ];
    }
}