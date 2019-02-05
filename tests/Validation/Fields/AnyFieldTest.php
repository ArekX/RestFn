<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license: http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\Validation\Fields;

use ArekX\JsonQL\Validation\BaseField;
use ArekX\JsonQL\Validation\Fields\AnyField;
use tests\Validation\Mocks\MockField;

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
            'identifier' => null,
            'notEmpty' => false,
            'emptyValue' => null
        ], $field->definition());
    }

    public function testDefinitionChangesWhenPropertiesSet()
    {
        $field = $this->createField()
            ->notEmpty()
            ->info('Info')
            ->identifier('Custom name')
            ->example('Example')
            ->emptyValue('');

        $this->assertEquals([
            'type' => 'any',
            'info' => 'Info',
            'example' => 'Example',
            'identifier' => 'Custom name',
            'notEmpty' => true,
            'emptyValue' => ''
        ], $field->definition());
    }

    public function testAlwaysValidatesWhenNotRequired()
    {
        $field = $this->createField();

        $this->assertEquals([], $field->validate(null));
        $this->assertEquals([], $field->validate(false));
        $this->assertEquals([], $field->validate('string'));
        $this->assertEquals([], $field->validate(1));
        $this->assertEquals([], $field->validate(1.5));
        $this->assertEquals([], $field->validate(new MockField()));
    }

    protected function createField(): AnyField
    {
        return \ArekX\JsonQL\Validation\anyField();
    }
}