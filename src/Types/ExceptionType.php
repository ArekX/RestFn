<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Types;

use function ArekX\JsonQL\Validation\anyType;
use function ArekX\JsonQL\Validation\arrayType;
use function ArekX\JsonQL\Validation\stringType;

class ExceptionType extends BaseType
{
    public static function fields(): array
    {
        return [
            'type' => stringType()->mustMatch('/[a-z0-9_-]+/')->required()->info('Type of the exception'),
            'data' => arrayType()->of(anyType())->info('Exception data'),
        ];
    }
}