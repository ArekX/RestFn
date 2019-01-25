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
use function ArekX\JsonQL\Validation\classType;
use function DI\string;

class DataProviderType extends BaseType
{
    public static function fields(): array
    {
        return [
            'pagination' => classType(PaginationType::class)->required()->info('Pagination information'),
            'sort' => classType(SortType::class)->required()->info('Sorting information'),
            'items' => arrayType()->required()->info('Item information')
        ];
    }
}