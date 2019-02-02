<?php

namespace tests\Validation\Fields;

use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Validation\BaseField;
use ArekX\JsonQL\Validation\Fields\AnyField;
use ArekX\JsonQL\Validation\Fields\NumberField;
use ArekX\JsonQL\Validation\Fields\StringField;
use tests\Validation\Mocks\MockField;

/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
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

    protected function createField(): AnyField
    {
        return DI::make(AnyField::class);
    }
}