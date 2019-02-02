<?php

namespace tests\Validation\Fields;

use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Validation\BaseField;
use ArekX\JsonQL\Validation\Fields\AnyField;
use ArekX\JsonQL\Validation\Fields\ArrayField;
use ArekX\JsonQL\Validation\Fields\NumberField;
use ArekX\JsonQL\Validation\Fields\StringField;
use tests\Validation\Mocks\MockField;

/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/
class ArrayFieldTest extends \tests\TestCase
{
    public function testInstanceOfBaseField()
    {
        $this->assertInstanceOf(BaseField::class, $this->createField());
    }

    public function testHasValidDefinition()
    {
        $field = $this->createField();
        $this->assertEquals([
            'type' => 'array',
            'info' => null,
            'example' => null,
            'required' => false,
            'emptyValue' => null
        ], $field->definition());
    }

    public function testDefinitionChangesWhenPropertiesSet()
    {
        $field = $this->createField()
            ->required()
            ->info('Info')
            ->example('Example')
            ->emptyValue('');

        $this->assertEquals([
            'type' => 'array',
            'info' => 'Info',
            'example' => 'Example',
            'required' => true,
            'emptyValue' => ''
        ], $field->definition());
    }

    public function stestValidatesArrayType()
    {
        $field = $this->createField();
        $this->assertEquals([], $field->validate('fieldName', []));
    }

    public function testFailsOnNonArrayType()
    {
        $field = $this->createField()->required();
        $error = [['type' => ArrayField::ERROR_NOT_AN_ARRAY]];
        $this->assertEquals($error, $field->validate('fieldName', null));
        $this->assertEquals($error, $field->validate('fieldName', ''));
        $this->assertEquals($error, $field->validate('fieldName', 0));
        $this->assertEquals($error, $field->validate('fieldName', 0.00));
        $this->assertEquals($error, $field->validate('fieldName', false));
        $this->assertEquals($error, $field->validate('fieldName', new MockField()));
    }

    public function testAnyItemIsValidIfTypeIsNotSet()
    {
        $field = $this->createField()->required();
        $this->assertEquals([], $field->validate('fieldName', ['1', 2, '3', true, null]));
    }

    public function testCanSetItemType()
    {
        $mock = new MockField();
        $field = $this->createField()->required();

        $this->assertNull($field->of);
        $this->assertEquals($field, $field->of($mock));
        $this->assertEquals($mock, $field->of);
    }

    public function testItemTypeIsValidated()
    {
        $mock = new MockField(['error1']);
        $field = $this->createField()->required()->of($mock);

        $this->assertEquals([
            [
                'type' => ArrayField::ERROR_ITEM_NOT_VALID,
                'items' => [
                    0 => ['error1'],
                    1 => ['error1'],
                    2 => ['error1']
                ]]
        ], $field->validate('fieldName', [1, 2, 3]));
    }

    public function testSelectiveItemIsValidated()
    {
        $mock = new StringField();
        $field = $this->createField()->required()->of($mock);

        $this->assertEquals([
            [
                'type' => ArrayField::ERROR_ITEM_NOT_VALID,
                'items' => [
                    1 => [['type' => StringField::ERROR_NOT_A_STRING]],
                ]]
        ], $field->validate('fieldName', ['1', 2, '3']));
    }

    protected function createField(): ArrayField
    {
        return DI::make(ArrayField::class);
    }
}