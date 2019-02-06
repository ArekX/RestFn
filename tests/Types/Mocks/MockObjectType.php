<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\Types\Mocks;

use ArekX\JsonQL\Validation\FieldInterface;

class MockObjectType extends \ArekX\JsonQL\Types\ObjectType
{
    /** @var string  */
    public static $typeName = 'Mock Base Type';

    /** @var FieldInterface[] */
    public static $fields = [];

    /**
     * Returns name of the type.
     *
     * @return string
     */
    public static function typeName(): string
    {
        return static::$typeName;
    }

    /**
     * Returns fields to be used for validation.
     *
     * @return \ArekX\JsonQL\Validation\FieldInterface[]|\ArekX\JsonQL\Validation\FieldInterface
     */
    public static function fields()
    {
        return static::$fields;
    }
}