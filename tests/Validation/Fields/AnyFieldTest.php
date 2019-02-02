<?php

namespace tests\Validation\Fields;

use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Validation\BaseField;
use ArekX\JsonQL\Validation\Fields\AnyField;
use ArekX\JsonQL\Validation\Fields\NumberField;
use ArekX\JsonQL\Validation\Fields\StringField;
use tests\Validation\Mocks\MockField;

/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
 **/
class AnyFieldTest extends \tests\TestCase
{
    public function testInstanceOfBaseField()
    {
        $this->assertInstanceOf(BaseField::class, $this->createField());
    }

    public function testHasValidDefinition()
    {
        $field = $this->createField();
        $this->assertEquals([
            'type' => 'any',
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
            'type' => 'any',
            'info' => 'Info',
            'example' => 'Example',
            'required' => true,
            'emptyValue' => ''
        ], $field->definition());
    }

    public function testAlwaysValidatesWhenNotRequired()
    {
        $field = $this->createField();

        $this->assertEquals([], $field->validate('fieldName', null));
        $this->assertEquals([], $field->validate('fieldName', false));
        $this->assertEquals([], $field->validate('fieldName', 'string'));
        $this->assertEquals([], $field->validate('fieldName', 1));
        $this->assertEquals([], $field->validate('fieldName', 1.5));
        $this->assertEquals([], $field->validate('fieldName', new MockField()));
    }

    protected function createField(): AnyField
    {
        return DI::make(AnyField::class);
    }
}