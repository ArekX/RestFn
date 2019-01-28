<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Types;

use function ArekX\JsonQL\Validation\numberField;

class PaginationType extends BaseType
{
    protected static function typeFields(): array
    {
        return [
            'page' => numberField()->required()->info('Current page'),
            'size' => numberField()->required()->info('Page size'),
            'total' => numberField()->required()->info('Total items')
        ];
    }
}