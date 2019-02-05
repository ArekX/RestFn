<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license: http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Types;


use ArekX\JsonQL\Validation\Fields\ObjectField;
use function ArekX\JsonQL\Validation\fromType;
use ArekX\JsonQL\Validation\TypeInterface;

abstract class BaseType implements TypeInterface
{
    /**
     * @inheritdoc
     */
    public static function definition(): array
    {
        return static::validator()->definition();
    }

    /**
     * @inheritdoc
     */
    public static function validator(): ObjectField
    {
        return fromType(static::class)->requiredKeys(static::requiredKeys());
    }

    protected static function requiredKeys()
    {
        return null;
    }
}