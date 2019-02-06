<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license: http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Types;


use ArekX\JsonQL\Validation\FieldInterface;
use ArekX\JsonQL\Validation\Fields\ObjectField;
use function ArekX\JsonQL\Validation\fromType;
use ArekX\JsonQL\Validation\TypeInterface;

abstract class BaseType implements TypeInterface
{
    /**
     * Returns name of the type.
     *
     * @return string
     */
    public abstract static function typeName(): string;

    /**
     * Returns definition of the type.
     *
     * @return array
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public static function definition(): array
    {
        return static::validator()->definition();
    }

    /**
     * Returns validator for the type.
     *
     * @return ObjectField
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public static function validator(): FieldInterface
    {
        return fromType(static::class)->requiredKeys(static::requiredKeys());
    }

    protected static function requiredKeys()
    {
        return null;
    }
}