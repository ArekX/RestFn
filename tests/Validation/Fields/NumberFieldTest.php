<?php

namespace tests\Validation\Fields;

use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Validation\BaseField;
use ArekX\JsonQL\Validation\Fields\NumberField;
use tests\Validation\Mocks\MockField;

/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
 **/
class NumberFieldTest extends \tests\TestCase
{
    public function testInstanceOfBaseField()
    {
        $this->assertInstanceOf(BaseField::class, $this->createField());
    }

    public function testHasValidDefinition()
    {
        $field = $this->createField();
        $this->assertEquals([
            'type' => 'number',
            'info' => null,
            'example' => null,
            'required' => false,
            'integerOnly' => false,
            'minimum' => null,
            'maximum' => null,
            'emptyValue' => null
        ], $field->definition());
    }

    public function testDefinitionChangesWhenPropertiesSet()
    {
        $field = $this->createField()
            ->required()
            ->emptyValue(0)
            ->min(10)
            ->max(15)
            ->integerOnly();
        $this->assertEquals([
            'type' => 'number',
            'info' => null,
            'example' => null,
            'required' => true,
            'integerOnly' => true,
            'minimum' => 10,
            'maximum' => 15,
            'emptyValue' => 0
        ], $field->definition());
    }

    public function testDoesNotRunIfNotRequiredAndEmptyValue()
    {
        $field = $this->createField()->required(false);
        $this->assertEquals([], $field->validate('fieldName', null));
    }

    public function testSetEmptyValueIsChecked()
    {
        $field = $this->createField();

        $field->required(false);
        $this->assertEquals([
            ['type' => NumberField::ERROR_NOT_A_NUMBER]
        ], $field->validate('fieldName', []));
        $field->emptyValue([]);
        $this->assertEquals([], $field->validate('fieldName', []));
    }

    public function testValidatesANumber()
    {
        $field = $this->createField()->required();
        $this->assertEquals([], $field->validate('fieldName', 0));
        $this->assertEquals([], $field->validate('fieldName', 0.00));
    }

    public function testFailsIfNotANumber()
    {
        $field = $this->createField()->required()->emptyValue(0);
        $this->assertEquals([['type' => NumberField::ERROR_NOT_A_NUMBER]], $field->validate('fieldName', null));
        $this->assertEquals([['type' => NumberField::ERROR_NOT_A_NUMBER]], $field->validate('fieldName', false));
        $this->assertEquals([['type' => NumberField::ERROR_NOT_A_NUMBER]], $field->validate('fieldName', []));
        $this->assertEquals([['type' => NumberField::ERROR_NOT_A_NUMBER]], $field->validate('fieldName', 'String'));
        $this->assertEquals([['type' => NumberField::ERROR_NOT_A_NUMBER]], $field->validate('fieldName', new MockField()));
    }

    public function testCanSetIntegerOnly()
    {
        $field = $this->createField();
        $this->assertFalse($field->integerOnly);
        $this->assertSame($field, $field->integerOnly());
        $this->assertEquals(true, $field->integerOnly);
        $this->assertSame($field, $field->integerOnly(true));
        $this->assertEquals(true, $field->integerOnly);
        $this->assertSame($field, $field->integerOnly(false));
        $this->assertEquals(false, $field->integerOnly);
    }

    public function testValidatesIntegerOnly()
    {
        $field = $this->createField()->required()->integerOnly();
        $this->assertEquals([], $field->validate('fieldName', 0));
        $this->assertEquals([
            ['type' => NumberField::ERROR_NOT_AN_INT]
        ], $field->validate('fieldName', 0.00));
    }

    public function testCanSetMin()
    {
        $field = $this->createField();
        $this->assertNull($field->min);
        $this->assertSame($field, $field->min(10));
        $this->assertEquals(10, $field->min);
    }


    public function testValidatesMinimum()
    {
        $field = $this->createField()->required()->min(10);
        $this->assertEquals([
            ['type' => NumberField::ERROR_LESS_THAN_MIN, 'min' => 10]
        ], $field->validate('fieldName', 0));
        $this->assertEquals([], $field->validate('fieldName', 10));
        $this->assertEquals([], $field->validate('fieldName', 11));
    }

    public function testCanSetMax()
    {
        $field = $this->createField();
        $this->assertNull($field->max);
        $this->assertSame($field, $field->max(10));
        $this->assertEquals(10, $field->max);
    }


    public function testValidatesMaximum()
    {
        $field = $this->createField()->required()->max(10);
        $this->assertEquals([
            ['type' => NumberField::ERROR_GREATER_THAN_MAX, 'max' => 10]
        ], $field->validate('fieldName', 11));
        $this->assertEquals([], $field->validate('fieldName', 9));
        $this->assertEquals([], $field->validate('fieldName', 10));
    }

    public function testSetMaxSmallerThanMinWheMinNull()
    {
        $field = $this->createField();
        $this->assertSame($field, $field->max(10));
        $this->assertNull($field->min);
    }

    public function testSetMinLargerThanMaxWheMaxNull()
    {
        $field = $this->createField();
        $this->assertSame($field, $field->min(100));
        $this->assertNull($field->max);
    }


    public function testSetMaxSmallerThanMinWheMinSet()
    {
        $field = $this->createField()
            ->min(50)
            ->max(10);

        $this->assertEquals(10, $field->max);
        $this->assertEquals(10, $field->min);
    }

    public function testSetMinLargerThanMaxWheMaxSet()
    {
        $field = $this->createField()
            ->max(60)
            ->min(100);

        $this->assertEquals(100, $field->max);
        $this->assertEquals(100, $field->min);
    }

    protected function createField(): NumberField
    {
        return DI::make(NumberField::class);
    }
}