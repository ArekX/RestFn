<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Types;

use ArekX\JsonQL\Interfaces\DefinitionInterface;
use ArekX\JsonQL\Traits\Memoize;
use ArekX\JsonQL\Validation\FieldInterface;
use ArekX\JsonQL\Validation\TypeInterface;
use ArekX\JsonQL\Validation\TypeValidatorInterface;

use function ArekX\JsonQL\Validation\classField;

abstract class BaseType implements TypeInterface, TypeValidatorInterface, DefinitionInterface
{
    use Memoize;

    public static function fields(): array
    {
        return static::staticMemoize(static::class . __METHOD__, function () {
            return static::typeFields();
        });
    }

    public static function validator(): FieldInterface
    {
        return static::staticMemoize(static::class . __METHOD__, function () {
            return classField(static::class)->required(true, true);
        });
    }

    public static function strictValidator(): FieldInterface
    {
        return static::staticMemoize(static::class . __METHOD__, function () {
            return classField(static::class)->required(true, true)->strict();
        });
    }

    public static function name(): string
    {
        return static::staticMemoize(static::class . __METHOD__, function () {
            return (new \ReflectionClass(static::class))->getShortName();
        });
    }

    public static function definition(): array
    {
        return static::staticMemoize(static::class . __METHOD__, function () {
            return static::strictValidator()->getDefinition();
        });
    }

    protected static abstract function typeFields(): array;
}