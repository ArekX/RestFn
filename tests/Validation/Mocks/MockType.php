<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\Validation\Mocks;

use ArekX\JsonQL\Validation\Fields\ObjectField;
use function ArekX\JsonQL\Validation\fromType;
use ArekX\JsonQL\Validation\TypeInterface;

class MockType implements TypeInterface
{
    /**
     * @inheritdoc
     */
    public static function typeName(): string
    {
        return 'Mock Type';
    }

    /**
     * @inheritdoc
     */
    public static function fields(): array
    {
        return [];
    }

    public static function validator(): ObjectField
    {
        return fromType(static::class);
    }


    /**
     * @inheritdoc
     */
    public static function definition(): array
    {
        return static::validator()->definition();
    }
}