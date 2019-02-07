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
use ArekX\JsonQL\Validation\Fields\StringField;
use tests\Validation\Mocks\MockField;

class StringFieldTest extends \tests\TestCase
{
    public function testInstanceOfBaseField()
    {
        $this->assertInstanceOf(BaseField::class, $this->createField());
    }

    public function testHasValidDefinition()
    {
        $field = $this->createField();
        $this->assertEquals([
            'type' => 'string',
            'info' => null,
            'example' => null,
            'allowEmpty' => false,
            'minLength' => null,
            'match' => null,
            'maxLength' => null,
            'encoding' => null,
            'identifier' => null,
            'emptyValue' => null
        ], $field->definition());
    }

    public function testDefinitionChangesWhenPropertiesSet()
    {
        $field = $this->createField()
            ->allowEmpty()
            ->info('Info')
            ->example('Example')
            ->min(10)
            ->max(30)
            ->match('/pattern/')
            ->encoding('UTF-8')
            ->emptyValue('');

        $this->assertEquals([
            'type' => 'string',
            'info' => 'Info',
            'example' => 'Example',
            'allowEmpty' =>true,
            'identifier' => null,
            'minLength' => 10,
            'maxLength' => 30,
            'encoding' => 'UTF-8',
            'match' => '/pattern/',
            'emptyValue' => ''
        ], $field->definition());
    }

    public function testStringFieldValidatesStrings()
    {
        $field = $this->createField();
        $this->assertEquals([], $field->validate('String'));
    }

    public function testFailsIfNotAString()
    {
        $field = $this->createField()->allowEmpty()->emptyValue('');
        $this->assertEquals([StringField::ERROR_NOT_A_STRING => true], $field->validate(null));
        $this->assertEquals([StringField::ERROR_NOT_A_STRING => true], $field->validate(false));
        $this->assertEquals([StringField::ERROR_NOT_A_STRING => true], $field->validate([]));
        $this->assertEquals([StringField::ERROR_NOT_A_STRING => true], $field->validate(0));
        $this->assertEquals([StringField::ERROR_NOT_A_STRING => true], $field->validate(0.00));
        $this->assertEquals([StringField::ERROR_NOT_A_STRING => true], $field->validate(new MockField()));
    }

    public function testCanSetMin()
    {
        $field = $this->createField();
        $this->assertNull($field->min);
        $this->assertSame($field, $field->min(10));
        $this->assertEquals(10, $field->min);
    }


    public function testValidatesMinimum()
    {
        $field = $this->createField()->allowEmpty()->min(10);
        $this->assertEquals([
            StringField::ERROR_LESS_THAN_MIN_LENGTH => 10
        ], $field->validate('stringof9'));
        $this->assertEquals([], $field->validate('stringof10'));
        $this->assertEquals([], $field->validate('stringof 11'));
    }

    public function testCanSetMax()
    {
        $field = $this->createField();
        $this->assertNull($field->max);
        $this->assertSame($field, $field->max(10));
        $this->assertEquals(10, $field->max);
    }

    public function testValidatesMaximum()
    {
        $field = $this->createField()->allowEmpty()->max(10);
        $this->assertEquals([
            StringField::ERROR_GREATER_THAN_MAX_LENGTH => 10
        ], $field->validate('very long string above 10 characters'));
        $this->assertEquals([], $field->validate('stringof10'));
        $this->assertEquals([], $field->validate('stringof9'));
    }

    public function testSetMaxSmallerThanMinWheMinNull()
    {
        $field = $this->createField();
        $this->assertSame($field, $field->max(10));
        $this->assertNull($field->min);
    }

    public function testSetMinLargerThanMaxWheMaxNull()
    {
        $field = $this->createField();
        $this->assertSame($field, $field->min(100));
        $this->assertNull($field->max);
    }


    public function testSetMaxSmallerThanMinWheMinSet()
    {
        $field = $this->createField()
            ->min(50)
            ->max(10);

        $this->assertEquals(10, $field->max);
        $this->assertEquals(10, $field->min);
    }

    public function testSetMinLargerThanMaxWheMaxSet()
    {
        $field = $this->createField()
            ->max(60)
            ->min(100);

        $this->assertEquals(100, $field->max);
        $this->assertEquals(100, $field->min);
    }

    public function testCanSeEncoding()
    {
        $field = $this->createField();
        $this->assertNull($field->encoding);
        $this->assertSame($field, $field->encoding('8bit'));
        $this->assertEquals('8bit', $field->encoding);
    }

    public function testUseEncodingForLengthValidation()
    {
        $field = $this->createField()->min(10);
        $this->assertNull($field->encoding);
        $this->assertSame($field, $field->encoding('8bit'));
        $this->assertEquals([], $field->validate('stringof10'));
    }

    public function testCanSetPattern()
    {
        $field = $this->createField();
        $this->assertNull($field->match);
        $this->assertSame($field, $field->match('/match/'));
        $this->assertEquals('/match/', $field->match);
    }

    public function testPatternWillMatch()
    {
        $field = $this->createField()->match('/^\w+$/');
        $this->assertEquals([], $field->validate('value'));
    }

    public function testPatternWillFail()
    {
        $field = $this->createField()->match('/^\w+$/');
        $this->assertEquals([
            StringField::ERROR_NOT_A_MATCH => '/^\w+$/'
        ], $field->validate('another Value'));
    }

    public function testErrorsAreAggregated()
    {
        $field = $this->createField()
            ->max(10)
            ->match('/^\w+$/');
        $this->assertEquals([
            StringField::ERROR_GREATER_THAN_MAX_LENGTH => 10,
            StringField::ERROR_NOT_A_MATCH => '/^\w+$/'
        ], $field->validate('another Value'));
    }

    public function testCanBeCreatedWithLength()
    {
        $field = $this->createField(100);
        $this->assertEquals(null, $field->min);
        $this->assertEquals(100, $field->max);
    }

    protected function createField($length = null): StringField
    {
        return \ArekX\JsonQL\Validation\stringField($length);
    }
}