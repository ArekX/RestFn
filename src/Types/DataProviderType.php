<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Types;

use function ArekX\JsonQL\Validation\arrayType;
use function ArekX\JsonQL\Validation\classField;

class DataProviderType extends BaseType
{
    protected static function typeFields(): array
    {
        return [
            'pagination' => classField(PaginationType::class)->required()->info('Pagination information'),
            'sort' => classField(SortType::class)->required()->info('Sorting information'),
            'items' => static::itemType()
        ];
    }

    protected static function itemType()
    {
        return arrayType()->required()->info('Item information');
    }
}