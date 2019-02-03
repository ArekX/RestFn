<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license: http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\Validation\Fields;

use ArekX\JsonQL\Validation\BaseField;
use ArekX\JsonQL\Validation\Fields\NumberField;
use ArekX\JsonQL\Validation\Fields\ObjectField;
use function ArekX\JsonQL\Validation\numberField;
use function ArekX\JsonQL\Validation\stringField;
use tests\Validation\Mocks\MockField;

class ObjectFieldTest extends \tests\TestCase
{
    public function testInstanceOfBaseField()
    {
        $this->assertInstanceOf(BaseField::class, $this->createField());
    }

    public function testHasValidDefinition()
    {
        $field = $this->createField([
            'test' => new MockField(),
            'test2' => new MockField([], ['test' => 1])
        ]);
        $this->assertEquals([
            'type' => 'object',
            'info' => null,
            'example' => null,
            'required' => false,
            'emptyValue' => null,
            'fields' => [
                'test' => [
                    'type' => 'mock',
                    'info' => null,
                    'example' => null,
                    'required' => false,
                    'emptyValue' => null
                ],
                'test2' => [
                    'type' => 'mock',
                    'info' => null,
                    'example' => null,
                    'required' => false,
                    'emptyValue' => null,
                    'test' => 1
                ]
            ]
        ], $field->definition());
    }

    public function testDefinitionChangesWhenPropertiesSet()
    {
        $field = $this->createField([])
            ->required()
            ->info('Info')
            ->example('Example')
            ->emptyValue('null');

        $this->assertEquals([
            'type' => 'object',
            'info' => 'Info',
            'example' => 'Example',
            'required' => true,
            'emptyValue' => 'null',
            'fields' => []
        ], $field->definition());
    }

    public function testCanSetFields()
    {
        $ob1 = ['ob' => new MockField()];
        $ob2 = ['ob2' => new MockField()];
        $field = $this->createField($ob1);
        $this->assertEquals($ob1, $field->fields);
        $this->assertEquals($field, $field->fields($ob2));
        $this->assertEquals($ob2, $field->fields);
    }

    public function testValidates()
    {
        $field = $this->createField([
            'key1' => stringField(),
            'key2' => numberField()
        ]);
        $this->assertEquals([], $field->validate([
            'key1' => 'string',
            'key2' => 1
        ]));
    }

    public function testFailsToValidate()
    {
        $field = $this->createField([
            'key' => numberField()
        ]);
        $this->assertEquals([
            [
                'type' => ObjectField::ERROR_INVALID_FIELDS,
                'fields' => [
                    'key' => [['type' => NumberField::ERROR_NOT_A_NUMBER]]
                ]
            ]
        ], $field->validate([
            'key' => '1'
        ]));
    }

    protected function createField(array $fields = []): ObjectField
    {
        return \ArekX\JsonQL\Validation\objectField($fields);
    }
}