<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\Validation\Fields;

use ArekX\JsonQL\Validation\BaseField;
use function ArekX\JsonQL\Validation\compare;
use ArekX\JsonQL\Validation\Fields\CompareField;

class CompareFieldTest extends \tests\TestCase
{
    public function testInstanceOfBaseField()
    {
        $this->assertInstanceOf(BaseField::class, $this->createField());
    }

    public function testHasValidDefinition()
    {
        $field = $this->createField();
        $this->assertEquals([
            'type' => 'compare',
            'info' => null,
            'example' => null,
            'allowEmpty' => false,
            'emptyValue' => null,
            'identifier' => null,
            'withField' => null,
            'withValue' => null,
            'operator' => null,
        ], $field->definition());
    }

    public function testDefinitionChangesWhenPropertiesSet()
    {
        $field = $this->createField()
            ->allowEmpty()
            ->info('Info')
            ->example('Example')
            ->withField('>=', 'fieldName')
            ->emptyValue('null');

        $this->assertEquals([
            'type' => 'compare',
            'info' => 'Info',
            'example' => 'Example',
            'allowEmpty' => true,
            'identifier' => null,
            'emptyValue' => 'null',
            'withField' => 'fieldName',
            'withValue' => null,
            'operator' => '>=',
        ], $field->definition());
    }

    public function testCanSetCompareField()
    {
        $field = $this->createField()->withField('>', 'testField');
        $this->assertEquals('>', $field->operator);
        $this->assertEquals('testField', $field->fieldName);
        $this->assertEquals(null, $field->value);
    }


    public function testCanSetCompareValue()
    {
        $field = $this->createField()->withValue('>', 'testField');
        $this->assertEquals('>', $field->operator);
        $this->assertEquals('testField', $field->value);
        $this->assertEquals(null, $field->fieldName);
    }

    public function testSettingFieldAfterValueResetsField()
    {
        $field = $this->createField();

        $field->withField('>', 'testField');
        $this->assertEquals('testField', $field->fieldName);
        $this->assertEquals(null, $field->value);

        $field->withValue('>', 2);
        $this->assertEquals(null, $field->fieldName);
        $this->assertEquals(2, $field->value);
    }


    public function testCompareValueIsValidated()
    {
        $field = $this->createField();

        $operators = [
            [11, '>', 10],
            [6, '<', 7],
            [10, '>=', 10],
            [9, '!>=', 10],
            [10, '!>', 10],
            [10, '!<', 10],
            [11, '!<=', 10],
            [9, '!=', 10],
            [10, '=', 10],
        ];

        foreach ($operators as $operator) {
            [$value, $op, $withValue] = $operator;
            $this->assertEquals([], $field->withValue($op, $withValue)->validate($value));
        }
    }

    public function testCompareValueIsFails()
    {
        $field = $this->createField();

        $operators = [
            [9, '>', 10],
            [8, '<', 7],
            [9, '>=', 10],
            [10, '!>=', 10],
            [11, '!>', 10],
            [9, '!<', 10],
            [10, '!<=', 10],
            [10, '!=', 10],
            [9, '=', 10],
        ];

        foreach ($operators as $operator) {
            [$value, $op, $withValue] = $operator;
            $error = [
                CompareField::ERROR_COMPARE_VALUE_FAILED => ['withValue' => $withValue, 'operator' => $op]
            ];

            $this->assertEquals($error, $field->withValue($op, $withValue)->validate($value));
        }
    }

    public function testCompareFieldIsValidated()
    {
        $field = $this->createField();
        $this->assertEquals([], $field->withField('>', 'testField')->validate(11, ['testField' => 10]));
        $this->assertEquals([], $field->withField('=', 'testField')->validate(10, ['testField' => 10]));
    }

    public function testCompareFieldFails()
    {
        $field = $this->createField();
        $error = [CompareField::ERROR_COMPARE_FIELD_FAILED => ['withField' => 'testField', 'operator' => '>']];
        $this->assertEquals($error, $field->withField('>', 'testField')->validate(9, ['testField' => 10]));
    }

    public function testComparisonWithNothingSetAlwaysPasses()
    {
        $field = $this->createField();
        $this->assertEquals([], $field->validate(9));
    }

    protected function createField(): CompareField
    {
        return compare();
    }
}