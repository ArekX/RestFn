<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license: http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\Values\Mock;

use ArekX\JsonQL\Validation\Fields\ObjectField;
use ArekX\JsonQL\Values\TypedValue;

class MockTypeValue extends TypedValue
{
    /**
     * @inheritdoc
     * @return MockType
     */
    public static function type(): string
    {
        return MockType::class;
    }

    protected static function getValidator(): ObjectField
    {
        return static::type()::validator();
    }

    public static function getParentValidator(): ObjectField
    {
        return parent::getValidator();
    }
}