<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Types;

use ArekX\JsonQL\Traits\Memoize;
use function ArekX\JsonQL\Validation\objectType;
use ArekX\JsonQL\Validation\Rules\ObjectRule;

abstract class BaseType implements TypeInterface
{
    use Memoize;

    public static function resolvedFields(): array
    {
        return static::staticMemoize(__METHOD__, function() {
            return static::fields();
        });
    }

    public static function getValidator(): ObjectRule
    {
        return static::staticMemoize(__METHOD__, function() {
           return objectType(static::resolvedFields());
        });
    }

    public static function name(): string
    {
        return static::staticMemoize(__METHOD__, function() {
            return (new \ReflectionClass(__CLASS__))->getShortName();
        });
    }

    public static function validate(array $data): array
    {
        ['data' => $results] = static::getValidator()->validate('_', $data, ['_' => $data]);
        return $results;
    }
}