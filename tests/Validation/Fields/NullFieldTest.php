<?php

namespace tests\Validation\Fields;

use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Validation\BaseField;
use ArekX\JsonQL\Validation\Fields\BoolField;
use ArekX\JsonQL\Validation\Fields\NullField;
use tests\Validation\Mocks\MockField;

/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
 **/
class NullFieldTest extends \tests\TestCase
{
    public function testInstanceOfBaseField()
    {
        $this->assertInstanceOf(BaseField::class, $this->createField());
    }

    public function testHasValidDefinition()
    {
        $field = $this->createField();
        $this->assertEquals([
            'type' => 'null',
            'info' => null,
            'example' => null,
            'required' => false,
            'emptyValue' => 0
        ], $field->definition());
    }

    public function testDefinitionChangesWhenPropertiesSet()
    {
        $field = $this->createField()
            ->required()
            ->info('Info')
            ->example('Example')
            ->emptyValue('null');

        $this->assertEquals([
            'type' => 'null',
            'info' => 'Info',
            'example' => 'Example',
            'required' => true,
            'emptyValue' => 'null'
        ], $field->definition());
    }

    public function testValidatesBoolType()
    {
        $field = $this->createField()->required();
        $this->assertEquals([], $field->validate('fieldName', null));
    }

    public function testFailsToValidateOtherTypes()
    {
        $field = $this->createField()->emptyValue(false);
        $error = [['type' => NullField::ERROR_NOT_A_NULL]];
        $this->assertEquals($error, $field->validate('fieldName', 1));
        $this->assertEquals($error, $field->validate('fieldName', 1.5));
        $this->assertEquals($error, $field->validate('fieldName', 'string'));
        $this->assertEquals($error, $field->validate('fieldName', new MockField()));
    }

    protected function createField(): NullField
    {
        return DI::make(NullField::class);
    }
}