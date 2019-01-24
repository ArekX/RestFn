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

class DataProviderType extends BaseType
{
    public static function fields(): array
    {
        return [
            'pagination' => subType(PaginationType::class)->info('Pagination information'),
            'sort' => subType(SortType::class)->info('Sorting information'),
            'items' => arrayType()->of(anyType())->info('Item information')
        ];
    }
}