<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license: http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\Validation\Fields;

use ArekX\JsonQL\Validation\BaseField;
use ArekX\JsonQL\Validation\Fields\BoolField;
use tests\Validation\Mocks\MockField;

class BoolFieldTest extends \tests\TestCase
{
    public function testInstanceOfBaseField()
    {
        $this->assertInstanceOf(BaseField::class, $this->createField());
    }

    public function testHasValidDefinition()
    {
        $field = $this->createField();
        $this->assertEquals([
            'type' => 'bool',
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
            ->emptyValue(false);

        $this->assertEquals([
            'type' => 'bool',
            'info' => 'Info',
            'example' => 'Example',
            'required' => true,
            'emptyValue' => false
        ], $field->definition());
    }


    public function testValidatesBoolType()
    {
        $field = $this->createField();
        $this->assertEquals([], $field->validate(true));
        $this->assertEquals([], $field->validate(false));
    }

    public function testFailsToValidateOtherTypes()
    {
        $field = $this->createField()->emptyValue(false);
        $error = [['type' => BoolField::ERROR_NOT_A_BOOL]];
        $this->assertEquals($error, $field->validate(1));
        $this->assertEquals($error, $field->validate(1.5));
        $this->assertEquals($error, $field->validate('string'));
        $this->assertEquals($error, $field->validate(null));
        $this->assertEquals($error, $field->validate(new MockField()));
    }

    protected function createField(): BoolField
    {
        return \ArekX\JsonQL\Validation\boolField();
    }
}