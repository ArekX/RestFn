<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Validation;

use ArekX\JsonQL\Validation\FieldInterface;
use ArekX\JsonQL\Validation\Fields\AllOfField;

class FunctionsTest extends \tests\TestCase
{
    public function testAllOfField()
    {
        $this->assertInstanceOf(AllOfField::class, \ArekX\JsonQL\Validation\allOf());
        $this->assertInstanceOf(FieldInterface::class, \ArekX\JsonQL\Validation\allOf());
    }
}