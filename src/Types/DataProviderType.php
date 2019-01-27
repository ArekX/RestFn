<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Types;

use function ArekX\JsonQL\Validation\arrayType;
use function ArekX\JsonQL\Validation\classType;

class DataProviderType extends BaseType
{
    protected static function typeFields(): array
    {
        return [
            'pagination' => classType(PaginationType::class)->required()->info('Pagination information'),
            'sort' => classType(SortType::class)->required()->info('Sorting information'),
            'items' => static::itemType()
        ];
    }

    protected static function itemType()
    {
        return arrayType()->required()->info('Item information');
    }
}