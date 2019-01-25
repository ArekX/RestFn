<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Types;

use ArekX\JsonQL\Traits\Memoize;
use function ArekX\JsonQL\Validation\objectType;
use ArekX\JsonQL\Validation\RuleInterface;

abstract class BaseType implements TypeInterface
{
    use Memoize;

    public static function resolvedFields(): array
    {
        return static::staticMemoize(__METHOD__, function() {
            return static::fields();
        });
    }

    public static function validator(): RuleInterface
    {
        return static::staticMemoize(__METHOD__, function() {
           return objectType(static::resolvedFields());
        });
    }

    public static function strictValidator(): RuleInterface
    {
        return static::staticMemoize(__METHOD__, function() {
           return objectType(static::resolvedFields())->strict();
        });
    }

    public static function name(): string
    {
        return static::staticMemoize(__METHOD__, function() {
            return (new \ReflectionClass(__CLASS__))->getShortName();
        });
    }
}