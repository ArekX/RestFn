<?php

use ArekX\JsonQL\Validation\Fields\ObjectField;
use ArekX\JsonQL\Validation\Fields\StringField;
use function ArekX\JsonQL\Validation\stringField;
use ArekX\JsonQL\Values\InvalidValueException;
use ArekX\JsonQL\Values\TypedValue;
use tests\Values\Mock\MockType;
use tests\Values\Mock\MockTypeValue;

/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license: http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/
class TypedValueTest extends \tests\TestCase
{
    public function testCanCreateTypedValue()
    {
        $value = MockTypeValue::from([]);
        $this->assertInstanceOf(TypedValue::class, $value);
    }

    public function testCanSetData()
    {
        MockType::$fields = ['key' => stringField()];
        $value = MockTypeValue::from(['key' => 'string']);

        $value->setData(['key' => 'value']);
        $this->assertEquals(['key' => 'value'], $value->getData());
    }

    public function testSettingDataTriggersValidation()
    {
        MockType::$fields = ['key' => stringField()];
        $value = MockTypeValue::from(['key' => 'string']);

        $this->assertValidationErrors(function () use ($value) {
            $value->setData(['key' => 1]);
        }, [
            [
                'type' => ObjectField::ERROR_INVALID_FIELDS,
                'fields' => [
                    'key' => [['type' => StringField::ERROR_NOT_A_STRING]]
                ]
            ]
        ]);
    }

    public function testCanSetOneItem()
    {
        MockType::$fields = ['key' => stringField()];
        $value = MockTypeValue::from(['key' => 'string']);

        $value->set('key', 'value');
        $this->assertEquals(['key' => 'value'], $value->getData());
    }

    public function testSetOneTriggersValidation()
    {
        MockType::$fields = ['key' => stringField()];
        $value = MockTypeValue::from(['key' => 'string']);

        $this->assertValidationErrors(function () use ($value) {
            $value->set('key', 1);
        }, [
            [
                'type' => ObjectField::ERROR_INVALID_FIELDS,
                'fields' => [
                    'key' => [['type' => StringField::ERROR_NOT_A_STRING]]
                ]
            ]
        ]);
    }

    public function testCanGetData()
    {
        MockType::$fields = ['key' => stringField()];
        $value = MockTypeValue::from([
            'key' => 'string'
        ]);

        $this->assertEquals(['key' => 'string'], $value->getData());
    }

    public function testCanGetSingleItem()
    {
        MockType::$fields = [
            'key1' => stringField(),
            'key2' => stringField(),
            'key3' => stringField()
        ];
        $value = MockTypeValue::from([
            'key1' => 'string1',
            'key2' => 'string2',
            'key3' => 'string3'
        ]);

        $this->assertEquals('string2', $value->get('key2'));
    }

    public function testValidationFails()
    {
        MockType::$fields = ['key' => stringField()];

        $this->assertValidationErrors(function () {
            MockTypeValue::from([
                'key' => 1
            ]);
        }, [
            [
                'type' => ObjectField::ERROR_INVALID_FIELDS,
                'fields' => [
                    'key' => [['type' => StringField::ERROR_NOT_A_STRING]]
                ]
            ]
        ]);
    }

    public function assertValidationErrors(callable $validatorAction, array $errors)
    {
        try {
            $validatorAction();
        } catch (InvalidValueException $e) {
            $this->assertEquals($errors, $e->validationErrors);
        }
    }
}