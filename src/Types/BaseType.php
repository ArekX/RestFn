<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Types;

use ArekX\JsonQL\Traits\Memoize;
use ArekX\JsonQL\Validation\FieldInterface;
use function ArekX\JsonQL\Validation\classType;
use ArekX\JsonQL\Validation\ValidatedTypeInterface;

abstract class BaseType implements TypeInterface, ValidatedTypeInterface
{
    use Memoize;

    public static function resolvedFields(): array
    {
        return static::staticMemoize(static::class . __METHOD__, function () {
            return static::fields();
        });
    }

    public static function validator(): FieldInterface
    {
        $key = static::class . __METHOD__;
        return static::staticMemoize($key, function () {
            return classType(static::class)->required(true, true);
        });
    }

    public static function strictValidator(): FieldInterface
    {
        $key = static::class . __METHOD__;
        return static::staticMemoize($key, function () {
            return classType(static::class)->required(true, true)->strict();
        });
    }

    public static function name(): string
    {
        return static::staticMemoize(static::class . __METHOD__, function () {
            return (new \ReflectionClass(static::class))->getShortName();
        });
    }
}