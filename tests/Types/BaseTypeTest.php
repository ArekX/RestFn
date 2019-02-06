<?php

namespace tests\Types;

use function ArekX\JsonQL\Validation\numberField;
use function ArekX\JsonQL\Validation\stringField;
use PHPUnit\Framework\MockObject\MockObject;
use tests\Types\Mocks\MockBaseType;
use tests\Types\Mocks\MockObjectType;

/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license: http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/
class BaseTypeTest extends \tests\TestCase
{
    public function testValidatorTypeNameIsSet()
    {
        MockObjectType::$typeName = 'Mock Type Name';
        MockObjectType::$fields = [
            'key1' => stringField(),
            'key2' => numberField()
        ];
        $this->assertValidDefinition();
    }

    protected function assertValidDefinition()
    {
        $fields = [];

        foreach (MockObjectType::$fields as $key => $field) {
            $fields[$key] = $field->definition();
        }

        $this->assertEquals([
            'type' => 'object',
            'identifier' => null,
            'info' => null,
            'example' => null,
            'allowEmpty' => false,
            'emptyValue' => null,
            'anyKey' => null,
            'typeName' => MockObjectType::$typeName,
            'requiredKeys' => null,
            'fields' => $fields
        ], MockObjectType::definition());
    }
}