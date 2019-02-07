<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\Values\Mock;

use ArekX\JsonQL\Validation\Fields\ObjectField;
use function ArekX\JsonQL\Validation\objectField;
use ArekX\JsonQL\Validation\TypeInterface;

class MockType implements TypeInterface
{
    public static $fields = [];

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
        return static::$fields;
    }

    /**
     * @inheritdoc
     */
    public static function definition(): array
    {
        return static::field()->definition();
    }

    /**
     * @inheritdoc
     */
    public static function field(): ObjectField
    {
        return objectField(static::fields());
    }
}