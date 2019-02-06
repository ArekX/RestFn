<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\Types\Mocks;

use ArekX\JsonQL\Validation\FieldInterface;

class MockBaseType extends \ArekX\JsonQL\Types\BaseType
{
    /** @var FieldInterface[] */
    public static $fields = [];

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