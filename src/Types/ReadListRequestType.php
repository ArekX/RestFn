<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Types;

use function ArekX\JsonQL\Validation\enumType;
use function ArekX\JsonQL\Validation\numberType;
use function ArekX\JsonQL\Validation\objectType;
use ArekX\JsonQL\Validation\Fields\ObjectField;

class ReadListRequestType extends BaseType
{
    protected static function typeFields(): array
    {
        return [
            'filter' => static::filterType(),
            'pagination' => static::paginationType(),
            'sort' => static::sortType()
        ];
    }

    protected static function filterType()
    {
        return objectType()->required()->info('Read list filter');
    }

    protected static function paginationType()
    {
        return objectType()->of([
            'page' => numberType()->required()->info('Current page to retrieve'),
            'size' => numberType()
                ->min(10)
                ->max(50)
                ->info('Number of items to get per page.')
        ])->info('Pagination information.');
    }

    protected static function sortType()
    {
        return objectType()->of([
            ObjectField::ANY_KEY => enumType(['ascending', 'descending'])
        ])->info('Sorting information');
    }

}