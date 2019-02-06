<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Types;


use ArekX\JsonQL\Validation\FieldInterface;
use function ArekX\JsonQL\Validation\fromType;
use ArekX\JsonQL\Validation\TypeInterface;

abstract class BaseType implements TypeInterface
{
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
     * @return FieldInterface
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public static function validator(): FieldInterface
    {
        return fromType(static::class);
    }
}