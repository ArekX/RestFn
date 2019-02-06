<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\Validation\Fields;

use ArekX\JsonQL\Validation\BaseField;
use ArekX\JsonQL\Validation\Fields\ArrayField;
use ArekX\JsonQL\Validation\Fields\StringField;
use tests\Validation\Mocks\MockField;

class ArrayFieldTest extends \tests\TestCase
{
    public function testInstanceOfBaseField()
    {
        $this->assertInstanceOf(BaseField::class, $this->createField());
    }

    public function testHasValidDefinition()
    {
        $field = $this->createField();
        $this->assertEquals([
            'type' => 'array',
            'info' => null,
            'example' => null,
            'allowEmpty' => false,
            'identifier' => null,
            'itemType' => null,
            'emptyValue' => null
        ], $field->definition());
    }

    public function testDefinitionChangesWhenPropertiesSet()
    {
        $field = $this->createField()
            ->allowEmpty()
            ->info('Info')
            ->example('Example')
            ->identifier('custom name')
            ->of(new MockField())
            ->emptyValue('');

        $this->assertEquals([
            'type' => 'array',
            'info' => 'Info',
            'example' => 'Example',
            'identifier' => 'custom name',
            'allowEmpty' =>true,
            'itemType' => [
                'type' => 'mock',
                'allowEmpty' => false,
                'emptyValue' => null,
                'info' => null,
                'identifier' => null,
                'example' => null
            ],
            'emptyValue' => ''
        ], $field->definition());
    }

    public function testValidatesArrayType()
    {
        $field = $this->createField();
        $this->assertEquals([], $field->validate([]));
    }

    public function testFailsOnNonArrayType()
    {
        $field = $this->createField()->allowEmpty()->emptyValue([]);
        $error = [['type' => ArrayField::ERROR_NOT_AN_ARRAY]];
        $this->assertEquals($error, $field->validate(null));
        $this->assertEquals($error, $field->validate(''));
        $this->assertEquals($error, $field->validate(0));
        $this->assertEquals($error, $field->validate(0.00));
        $this->assertEquals($error, $field->validate(false));
        $this->assertEquals($error, $field->validate(new MockField()));
    }

    public function testAnyItemIsValidIfTypeIsNotSet()
    {
        $field = $this->createField()->allowEmpty();
        $this->assertEquals([], $field->validate(['1', 2, '3', true, null]));
    }

    public function testCanSetItemType()
    {
        $mock = new MockField();
        $field = $this->createField()->allowEmpty();

        $this->assertNull($field->of);
        $this->assertEquals($field, $field->of($mock));
        $this->assertEquals($mock, $field->of);
    }

    public function testItemTypeIsValidated()
    {
        $mock = new MockField(['error1']);
        $field = $this->createField()->allowEmpty()->of($mock);

        $this->assertEquals([
            [
                'type' => ArrayField::ERROR_ITEM_NOT_VALID,
                'items' => [
                    0 => ['error1'],
                    1 => ['error1'],
                    2 => ['error1']
                ]]
        ], $field->validate([1, 2, 3]));
    }

    public function testSelectiveItemIsValidated()
    {
        $mock = new StringField();
        $field = $this->createField()->allowEmpty()->of($mock);

        $this->assertEquals([
            [
                'type' => ArrayField::ERROR_ITEM_NOT_VALID,
                'items' => [
                    1 => [['type' => StringField::ERROR_NOT_A_STRING]],
                ]]
        ], $field->validate(['1', 2, '3']));
    }

    public function testAllItemAreValidated()
    {
        $mock = new StringField();
        $field = $this->createField()->allowEmpty()->of($mock);
        $this->assertEquals([], $field->validate(['1', '2', '3']));
    }

    protected function createField(): ArrayField
    {
        return \ArekX\JsonQL\Validation\arrayField();
    }
}