<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Types;

use function ArekX\JsonQL\Validation\anyType;
use function ArekX\JsonQL\Validation\enumType;
use function ArekX\JsonQL\Validation\nullType;
use function ArekX\JsonQL\Validation\numberType;
use function ArekX\JsonQL\Validation\objectType;
use function ArekX\JsonQL\Validation\orType;
use function ArekX\JsonQL\Validation\stringType;
use function ArekX\JsonQL\Validation\subType;
use function DI\string;

class PaginationType extends BaseType
{
    public function fields(): array
    {
        return [
            'page' => numberType()->required()->info('Current page'),
            'size' => numberType()->required()->info('Page size'),
            'total' => numberType()->required()->info('Total items')
        ];
    }
}