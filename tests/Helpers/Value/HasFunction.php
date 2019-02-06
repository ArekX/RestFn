<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\Helpers\Value;


use ArekX\JsonQL\Helpers\Value;

trait HasFunction
{
    public function testHasValueReturnsFalseOnNonObject()
    {
        $values = [
            null,
            "",
            0,
            0.00
        ];

        foreach ($values as $value) {

            $this->assertFalse(Value::has($value, 'testValue'));
        }
    }

    public function testHasValueReturnsFalseOnEmptyArray()
    {
        $values = [];
        $this->assertFalse(Value::has($values, 'testValue'));
    }

    public function testHasValueReturnsTrueWhenExistsInArray()
    {
        $values = ['testValue' => null];
        $this->assertTrue(Value::has($values, 'testValue'));
    }

    public function testHasValueReturnsFalseOnEmptyObject()
    {
        $values = new \stdClass();
        $this->assertFalse(Value::has($values, 'testValue'));
    }

    public function testHasValueReturnsTrueWhenExistsInObject()
    {
        $values = new \stdClass();
        $values->testValue = null;
        $this->assertTrue(Value::has($values, 'testValue'));
    }
}