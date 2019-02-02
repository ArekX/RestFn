<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Validation;

use ArekX\JsonQL\Validation\FieldInterface;
use ArekX\JsonQL\Validation\Fields\AllOfField;
use ArekX\JsonQL\Validation\Fields\AnyOfField;
use ArekX\JsonQL\Validation\Fields\NumberField;

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
}