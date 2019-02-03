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
        $this->assertFalse($field->notEmpty);
        $this->assertSame($field, $field->notEmpty());
        $this->assertTrue($field->notEmpty);
        $this->assertSame($field, $field->notEmpty(false));
        $this->assertFalse($field->notEmpty);
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
        $field->notEmpty(false);
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

        $field->notEmpty(false);
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
            'info' => null,
            'example' => null,
            'notEmpty' => false,
        ], $field->definition());
    }

    public function testDefinitionChangesIfPropertiesAreSet()
    {
        $field = $this->createField();

        $field
            ->notEmpty(true)
            ->emptyValue('test value')
            ->info('Info')
            ->example('Example');

        $this->assertEquals([
            'type' => 'mock',
            'emptyValue' => 'test value',
            'info' => 'Info',
            'example' => 'Example',
            'notEmpty' => true,
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
            'notEmpty' => false,
        ], $field->definition());
    }


    public function testRequiredIsChecked()
    {
        $field = $this->createField()->notEmpty(true);
        $this->assertEquals([['type' => BaseField::ERROR_VALUE_IS_EMPTY]], $field->validate(null));
    }

    public function testRequiredIsCheckedWithEmptyValue()
    {
        $field = $this->createField()->notEmpty(true)->emptyValue('');
        $this->assertEquals([['type' => BaseField::ERROR_VALUE_IS_EMPTY]], $field->validate(''));
    }

    protected function createField($validation = [], $definition = []): MockField
    {
        return DI::make(MockField::class, [
            'validation' => $validation,
            'definition' => $definition
        ]);
    }
}