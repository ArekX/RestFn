<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Validation;


use ArekX\JsonQL\Helpers\DI;
use tests\TestCase;
use tests\Validation\Mocks\MockField;

class BaseFieldTest extends TestCase
{
    public function testSettingRequired()
    {
        $field = $this->createField();
        $this->assertFalse($field->isRequired);
        $this->assertSame($field->required(), $field);
        $this->assertTrue($field->isRequired);
    }

    public function testSettingRequiredToNonRequired()
    {
        $field = $this->createField();
        $this->assertSame($field->required(), $field);
        $this->assertTrue($field->isRequired);
        $this->assertSame($field->required(false), $field);
        $this->assertFalse($field->isRequired);
    }

    public function testDoesNotRunIfNotRequiredAndEmptyValue()
    {
        $field = $this->createField(['error1']);
        $field->required(false);
        $this->assertEquals($field->validate('fieldName', null), []);
    }

    public function testCanSetEmptyValue()
    {
        $field = $this->createField();
        $this->assertNull($field->emptyValue);
        $this->assertSame($field->emptyValue(""), $field);
        $this->assertEquals($field->emptyValue, "");
    }

    public function testSetEmptyValueIsChecked()
    {
        $field = $this->createField(['error1']);

        $field->required(false);
        $this->assertEquals($field->validate('fieldName', []), ['error1']);
        $field->emptyValue([]);
        $this->assertEquals($field->validate('fieldName', []), []);
    }

    public function testDefinitionIsReturned()
    {
        $field = $this->createField();

        $this->assertEquals([
            'type' => 'mock',
            'emptyValue' => null,
            'required' => false,
        ], $field->definition());
    }

    public function testDefinitionChangesIfRequiredAndEmptyValueSet()
    {
        $field = $this->createField();

        $field->required(true);
        $field->emptyValue('testvalue');

        $this->assertEquals([
            'type' => 'mock',
            'emptyValue' => 'testvalue',
            'required' => true,
        ], $field->definition());
    }

    public function testExtendedClassOverridesDefinition()
    {
        $field = $this->createField([], ['type' => 'test']);

        $this->assertEquals([
            'type' => 'test',
            'emptyValue' => null,
            'required' => false,
        ], $field->definition());
    }


    protected function createField($validation = [], $definition = []): MockField
    {
        return DI::make(MockField::class, [
            'validation' => $validation,
            'definition' => $definition
        ]);
    }
}