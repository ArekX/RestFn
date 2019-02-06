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
        $this->assertFalse($field->allowEmpty);
        $this->assertSame($field, $field->allowEmpty());
        $this->assertTrue($field->allowEmpty);
        $this->assertSame($field, $field->allowEmpty(false));
        $this->assertFalse($field->allowEmpty);
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
        $field->allowEmpty(true);
        $this->assertEquals([], $field->validate(null));
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

        $field->allowEmpty(true);
        $this->assertEquals(['error1'], $field->validate([]));
        $field->emptyValue([]);
        $this->assertEquals([], $field->validate([]));
    }

    public function testDefinitionIsReturned()
    {
        $field = $this->createField();

        $this->assertEquals([
            'type' => 'mock',
            'emptyValue' => null,
            'identifier' => null,
            'info' => null,
            'example' => null,
            'allowEmpty' => false,
        ], $field->definition());
    }

    public function testDefinitionChangesIfPropertiesAreSet()
    {
        $field = $this->createField();

        $field
            ->allowEmpty(true)
            ->emptyValue('test value')
            ->info('Info')
            ->example('Example');

        $this->assertEquals([
            'type' => 'mock',
            'emptyValue' => 'test value',
            'info' => 'Info',
            'example' => 'Example',
            'identifier' => null,
            'allowEmpty' =>true,
        ], $field->definition());
    }

    public function testExtendedClassOverridesDefinition()
    {
        $field = $this->createField([], ['type' => 'test']);

        $this->assertEquals([
            'type' => 'test',
            'emptyValue' => null,
            'identifier' => null,
            'info' => null,
            'example' => null,
            'allowEmpty' => false,
        ], $field->definition());
    }


    public function testAllowEmptyIsChecked()
    {
        $field = $this->createField()->allowEmpty(false);
        $this->assertEquals([['type' => BaseField::ERROR_VALUE_IS_EMPTY]], $field->validate(null));
    }

    public function testRequiredIsCheckedWithEmptyValue()
    {
        $field = $this->createField()->allowEmpty(false)->emptyValue('');
        $this->assertEquals([['type' => BaseField::ERROR_VALUE_IS_EMPTY]], $field->validate(''));
    }


    public function testIdentifierIsSet()
    {
        $field = $this->createField();
        $this->assertNull($field->identifier);
        $this->assertSame($field, $field->identifier(""));
        $this->assertEquals("", $field->identifier);
    }

    protected function createField($validation = [], $definition = []): MockField
    {
        return DI::make(MockField::class, [
            'validation' => $validation,
            'definition' => $definition
        ]);
    }
}