<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Types;

use function ArekX\JsonQL\Validation\enumField;
use function ArekX\JsonQL\Validation\numberField;
use function ArekX\JsonQL\Validation\objectField;
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
        return objectField()->required()->info('Read list filter');
    }

    protected static function paginationType()
    {
        return objectField()->of([
            'page' => numberField()->required()->info('Current page to retrieve'),
            'size' => numberField()
                ->min(10)
                ->max(50)
                ->info('Number of items to get per page.')
        ])->info('Pagination information.');
    }

    protected static function sortType()
    {
        return objectField()->of([
            ObjectField::ANY_KEY => enumField(['ascending', 'descending'])
        ])->info('Sorting information');
    }

}