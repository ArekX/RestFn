<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\Validation\Fields;

use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Validation\BaseField;
use ArekX\JsonQL\Validation\Fields\NullField;
use tests\Validation\Mocks\MockField;

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
            'identifier' => null,
            'allowEmpty' => false,
            'emptyValue' => 0
        ], $field->definition());
    }

    public function testDefinitionChangesWhenPropertiesSet()
    {
        $field = $this->createField()
            ->allowEmpty()
            ->info('Info')
            ->example('Example')
            ->emptyValue('null');

        $this->assertEquals([
            'type' => 'null',
            'info' => 'Info',
            'example' => 'Example',
            'allowEmpty' =>true,
            'identifier' => null,
            'emptyValue' => 'null'
        ], $field->definition());
    }

    public function testValidates()
    {
        $field = $this->createField()->allowEmpty();
        $this->assertEquals([], $field->validate(null));
    }

    public function testFailsToValidateOtherTypes()
    {
        $field = $this->createField()->emptyValue(false);
        $error = [['type' => NullField::ERROR_NOT_A_NULL]];
        $this->assertEquals($error, $field->validate(1));
        $this->assertEquals($error, $field->validate(1.5));
        $this->assertEquals($error, $field->validate('string'));
        $this->assertEquals($error, $field->validate(new MockField()));
    }

    protected function createField(): NullField
    {
        return DI::make(NullField::class);
    }
}