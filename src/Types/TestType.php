<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Types;

use function ArekX\JsonQL\Validation\orFor;
use function ArekX\JsonQL\Validation\stringType;
use function ArekX\JsonQL\Validation\andFor;
use function ArekX\JsonQL\Validation\compareType;

class TestType implements TypeInterface
{
    public function fields(): array
    {
        return [
            'name' => stringType()->min(255)->info('User name'),
            'password' => andFor(
                stringType()->min(255),
                compareType()->not()->equalTo('confirmPassword'),
                orFor(
                    compareType()->not()->equalTo('name')
                )
            )->info("User's password"),
        ];
    }

    public function toArray(): array
    {
        // TODO: Implement toArray() method.
    }
}