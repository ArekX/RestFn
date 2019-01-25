<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Types;

use function ArekX\JsonQL\Validation\numberType;

class PaginationType extends BaseType
{
    public static function fields(): array
    {
        return [
            'page' => numberType()->required()->info('Current page'),
            'size' => numberType()->required()->info('Page size'),
            'total' => numberType()->required()->info('Total items')
        ];
    }
}