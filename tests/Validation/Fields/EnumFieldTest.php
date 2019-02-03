<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license: http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\Validation\Fields;

use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Validation\BaseField;
use ArekX\JsonQL\Validation\Fields\EnumField;

class EnumFieldTest extends \tests\TestCase
{
    public function testInstanceOfBaseField()
    {
        $this->assertInstanceOf(BaseField::class, $this->createField([]));
    }

    public function testHasValidDefinition()
    {
        $field = $this->createField([]);
        $this->assertEquals([
            'type' => 'enum',
            'info' => null,
            'example' => null,
            'required' => false,
            'emptyValue' => null,
            'values' => []
        ], $field->definition());
    }

    public function testDefinitionChangesWhenPropertiesSet()
    {
        $field = $this->createField([1, 2, 3])
            ->required()
            ->info('Info')
            ->example('Example')
            ->emptyValue('null');

        $this->assertEquals([
            'type' => 'enum',
            'info' => 'Info',
            'example' => 'Example',
            'required' => true,
            'emptyValue' => 'null',
            'values' => [1, 2, 3]
        ], $field->definition());
    }

    public function testValidatesIfInEnum()
    {
        $field = $this->createField([1, 2, '3']);
        $this->assertEquals([], $field->validate(1));
        $this->assertEquals([], $field->validate(2));
        $this->assertEquals([], $field->validate('3'));
    }

    public function testFailsValidationIfNotInEnum()
    {
        $field = $this->createField([1, 2, '3']);
        $error = [['type' => EnumField::ERROR_NOT_VALID_VALUE, 'valid' => [1, 2, '3']]];
        $this->assertEquals($error, $field->validate(4));
        $this->assertEquals($error, $field->validate(true));
        $this->assertEquals($error, $field->validate('2'));
        $this->assertEquals($error, $field->validate('1'));
    }

    protected function createField(array $values): EnumField
    {
        return DI::make(EnumField::class, ['values' => $values]);
    }
}