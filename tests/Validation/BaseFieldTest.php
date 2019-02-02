<?php
/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
 **/

namespace tests\Validation;


use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Validation\BaseField;
use tests\TestCase;
use tests\Validation\Mocks\MockField;

class BaseFieldTest extends TestCase
{
    public function testSettingRequired()
    {
        $field = $this->createField();
        $this->assertFalse($field->isRequired);
        $this->assertSame($field, $field->required());
        $this->assertTrue($field->isRequired);
        $this->assertSame($field, $field->required(false));
        $this->assertFalse($field->isRequired);
    }

    public function testCanSetInfo()
    {
        $field = $this->createField();
        $this->assertNull($field->info);
        $this->assertSame($field, $field->info('Info'));
        $this->assertEquals('Info', $field->info);
    }

    public function testCanSetExample()
    {
        $field = $this->createField();
        $this->assertNull($field->example);
        $this->assertSame($field, $field->example('Example'));
        $this->assertEquals('Example', $field->example);
    }

    public function testDoesNotRunIfNotRequiredAndEmptyValue()
    {
        $field = $this->createField(['error1']);
        $field->required(false);
        $this->assertEquals([], $field->validate('fieldName', null));
    }

    public function testCanSetEmptyValue()
    {
        $field = $this->createField();
        $this->assertNull($field->emptyValue);
        $this->assertSame($field, $field->emptyValue(""));
        $this->assertEquals("", $field->emptyValue);
    }

    public function testSetEmptyValueIsChecked()
    {
        $field = $this->createField(['error1']);

        $field->required(false);
        $this->assertEquals(['error1'], $field->validate('fieldName', []));
        $field->emptyValue([]);
        $this->assertEquals([], $field->validate('fieldName', []));
    }

    public function testDefinitionIsReturned()
    {
        $field = $this->createField();

        $this->assertEquals([
            'type' => 'mock',
            'emptyValue' => null,
            'info' => null,
            'example' => null,
            'required' => false,
        ], $field->definition());
    }

    public function testDefinitionChangesIfPropertiesAreSet()
    {
        $field = $this->createField();

        $field
            ->required(true)
            ->emptyValue('testvalue')
            ->info('Info')
            ->example('Example');

        $this->assertEquals([
            'type' => 'mock',
            'emptyValue' => 'testvalue',
            'info' => 'Info',
            'example' => 'Example',
            'required' => true,
        ], $field->definition());
    }

    public function testExtendedClassOverridesDefinition()
    {
        $field = $this->createField([], ['type' => 'test']);

        $this->assertEquals([
            'type' => 'test',
            'emptyValue' => null,
            'info' => null,
            'example' => null,
            'required' => false,
        ], $field->definition());
    }


    public function testRequiredIsChecked()
    {
        $field = $this->createField()->required(true);
        $this->assertEquals([['type' => BaseField::ERROR_VALUE_IS_REQUIRED]], $field->validate('fieldName', null));
    }

    public function testRequiredIsCheckedWithEmptyValue()
    {
        $field = $this->createField()->required(true)->emptyValue('');
        $this->assertEquals([['type' => BaseField::ERROR_VALUE_IS_REQUIRED]], $field->validate('fieldName', ''));
    }

    protected function createField($validation = [], $definition = []): MockField
    {
        return DI::make(MockField::class, [
            'validation' => $validation,
            'definition' => $definition
        ]);
    }
}