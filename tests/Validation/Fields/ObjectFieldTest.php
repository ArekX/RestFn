<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\Validation\Fields;

use ArekX\JsonQL\Validation\BaseField;
use ArekX\JsonQL\Validation\Fields\NumberField;
use ArekX\JsonQL\Validation\Fields\ObjectField;
use ArekX\JsonQL\Validation\Fields\StringField;
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
            'typeName' => null,
            'info' => null,
            'example' => null,
            'allowEmpty' => false,
            'emptyValue' => null,
            'identifier' => null,
            'anyKey' => null,
            'requiredKeys' => null,
            'fields' => [
                'test' => [
                    'type' => 'mock',
                    'info' => null,
                    'identifier' => null,
                    'example' => null,
                    'allowEmpty' => false,
                    'emptyValue' => null
                ],
                'test2' => [
                    'type' => 'mock',
                    'info' => null,
                    'identifier' => null,
                    'example' => null,
                    'allowEmpty' => false,
                    'emptyValue' => null,
                    'test' => 1
                ]
            ]
        ], $field->definition());
    }

    public function testDefinitionChangesWhenPropertiesSet()
    {
        $field = $this->createField([])
            ->allowEmpty()
            ->info('Info')
            ->example('Example')
            ->emptyValue('null')
            ->typeName('Test Type')
            ->requiredKeys([])
            ->anyKey(new MockField([], ['anyKey' => true]));

        $this->assertEquals([
            'type' => 'object',
            'typeName' => 'Test Type',
            'info' => 'Info',
            'example' => 'Example',
            'allowEmpty' =>true,
            'identifier' => null,
            'emptyValue' => 'null',
            'requiredKeys' => [],
            'anyKey' => [
                'type' => 'mock',
                'info' => null,
                'example' => null,
                'allowEmpty' => false,
                'emptyValue' => null,
                'identifier' => null,
                'anyKey' => true
            ],
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

    public function testFailsToValidateWhenNotAnObject()
    {
        $field = $this->createField([
            'key' => numberField()
        ]);
        $this->assertEquals([['type' => ObjectField::ERROR_NOT_AN_ASSOCIATIVE]], $field->validate(''));
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

    public function testCanSetAnyField()
    {
        $field = $this->createField();
        $anyKeyField = new MockField();
        $this->assertNull($field->anyKey);
        $this->assertEquals($field, $field->anyKey($anyKeyField));
        $this->assertEquals($anyKeyField, $field->anyKey);
    }

    public function testValidatesAnyField()
    {
        $field = $this->createField()->anyKey(numberField());
        $this->assertEquals([], $field->validate([
            'key1' => 1,
            'key2' => 2,
            'key3' => 3
        ]));
    }

    public function testFailsToValidateAnyField()
    {
        $field = $this->createField()->anyKey(numberField());
        $this->assertEquals([
            [
                'type' => ObjectField::ERROR_INVALID_FIELDS,
                'fields' => [
                    'key2' => [['type' => NumberField::ERROR_NOT_A_NUMBER]]
                ]
            ]
        ], $field->validate([
            'key1' => 1,
            'key2' => '2',
            'key3' => 3
        ]));
    }


    public function testCanSetRequiredKeys()
    {
        $field = $this->createField();
        $this->assertNull($field->requiredKeys);
        $this->assertEquals($field, $field->requiredKeys());
        $this->assertNull($field->requiredKeys);
        $this->assertEquals($field, $field->requiredKeys([]));
        $this->assertEquals([], $field->requiredKeys);
        $this->assertEquals($field, $field->requiredKeys(['key1', 'key2', 'key3']));
        $this->assertEquals(['key1', 'key2', 'key3'], $field->requiredKeys);
    }

    public function testMissingKeysNotAllowed()
    {
        $field = $this->createField([
            'key1' => stringField(),
            'key2' => numberField()
        ]);
        $this->assertEquals([
            [
                'type' => ObjectField::ERROR_MISSING_KEYS,
                'keys' => ['key1']
            ]
        ], $field->validate([
            'key2' => 3
        ]));
    }

    public function testSpecificMissingKeys()
    {
        $field = $this->createField([
            'key1' => stringField(),
            'key2' => numberField(),
            'key3' => numberField()
        ])->requiredKeys(['key3']);
        $this->assertEquals([
            [
                'type' => ObjectField::ERROR_MISSING_KEYS,
                'keys' => ['key3']
            ]
        ], $field->validate([
            'key2' => 3
        ]));
    }

    public function testCanSetStrictKeys()
    {
        $field = $this->createField();
        $this->assertFalse($field->strictKeys);
        $this->assertEquals($field, $field->strictKeys());
        $this->assertTrue($field->strictKeys);
        $this->assertEquals($field, $field->strictKeys(false));
        $this->assertFalse($field->strictKeys);
    }

    public function testErrorsAreAggregated()
    {
        $field = $this->createField([
            'key1' => stringField(),
            'key2' => stringField()
        ])->strictKeys();

        $this->assertEquals([
            [
                'type' => ObjectField::ERROR_INVALID_FIELDS,
                'fields' => [
                    'key2' => [
                        ['type' => StringField::ERROR_NOT_A_STRING]
                    ]
                ]
            ],
            [
                'type' => ObjectField::ERROR_MISSING_KEYS,
                'keys' => ['key1']
            ],
            [
                'type' => ObjectField::ERROR_INVALID_FIELD_KEYS,
                'keys' => ['unknownKey']
            ]
        ], $field->validate([
            'key2' => 3,
            'unknownKey' => true
        ]));
    }

    public function testCanSetOverride()
    {
        $field = $this->createField();
        $this->assertEquals([], $field->fields);

        $override = ['key' => stringField()];
        $field->merge($override);

        $this->assertEquals($override, $field->fields);
    }


    public function testCanSetTypeName()
    {
        $field = $this->createField();
        $this->assertEquals(null, $field->typeName);
        $field->typeName('typeName');
        $this->assertEquals('typeName', $field->typeName);
    }

    protected function createField(array $fields = []): ObjectField
    {
        return \ArekX\JsonQL\Validation\objectField($fields);
    }
}