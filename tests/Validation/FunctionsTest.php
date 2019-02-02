<?php
/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
 **/

namespace tests\Validation;

use ArekX\JsonQL\Validation\FieldInterface;
use ArekX\JsonQL\Validation\Fields\AllOfField;
use ArekX\JsonQL\Validation\Fields\AnyField;
use ArekX\JsonQL\Validation\Fields\AnyOfField;
use ArekX\JsonQL\Validation\Fields\ArrayField;
use ArekX\JsonQL\Validation\Fields\NumberField;
use ArekX\JsonQL\Validation\Fields\StringField;

class FunctionsTest extends \tests\TestCase
{
    public function testAllOfField()
    {
        $this->assertInstanceOf(AllOfField::class, \ArekX\JsonQL\Validation\allOf());
        $this->assertInstanceOf(FieldInterface::class, \ArekX\JsonQL\Validation\allOf());
    }

    public function testAnyOfField()
    {
        $this->assertInstanceOf(AnyOfField::class, \ArekX\JsonQL\Validation\anyOf());
        $this->assertInstanceOf(FieldInterface::class, \ArekX\JsonQL\Validation\anyOf());
    }


    public function testNumberField()
    {
        $this->assertInstanceOf(NumberField::class, \ArekX\JsonQL\Validation\numberField());
        $this->assertInstanceOf(FieldInterface::class, \ArekX\JsonQL\Validation\numberField());
    }

    public function testStringField()
    {
        $this->assertInstanceOf(StringField::class, \ArekX\JsonQL\Validation\stringField());
        $this->assertInstanceOf(FieldInterface::class, \ArekX\JsonQL\Validation\stringField());
    }


    public function testAnyField()
    {
        $this->assertInstanceOf(AnyField::class, \ArekX\JsonQL\Validation\anyField());
        $this->assertInstanceOf(FieldInterface::class, \ArekX\JsonQL\Validation\anyField());
    }


    public function testArrayField()
    {
        $this->assertInstanceOf(ArrayField::class, \ArekX\JsonQL\Validation\arrayField());
        $this->assertInstanceOf(FieldInterface::class, \ArekX\JsonQL\Validation\arrayField());
    }
}