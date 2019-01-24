<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Types;

use function ArekX\JsonQL\Validation\anyType;
use function ArekX\JsonQL\Validation\arrayType;
use function ArekX\JsonQL\Validation\enumType;
use function ArekX\JsonQL\Validation\nullType;
use function ArekX\JsonQL\Validation\objectType;
use function ArekX\JsonQL\Validation\orType;
use function ArekX\JsonQL\Validation\stringType;
use function ArekX\JsonQL\Validation\subType;
use function DI\string;

class ValidationErrorType extends BaseType
{
    public function fields(): array
    {
        return [
            'type' => stringType()->mustMatch('/[a-z0-9_-]+/')->required(),
            'data' => arrayType(subType(new PaginationType())),
            'field' => orType(
                subType([
                    'a' => stringType(),
                    'b' => stringType()
                ]),
                nullType()
            )
        ];
    }
}