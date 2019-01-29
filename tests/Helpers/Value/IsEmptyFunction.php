<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Helpers\Value;


use ArekX\JsonQL\Helpers\Value;

trait IsEmptyFunction
{
    public function testIsEmptyStrictChecks()
    {
        $values = [
            ["", true],
            [[], true],
            [0, true],
            [0.00, true],
            ["0", false],
            ["0.00", false],
            [1, false]
        ];

        foreach ($values as $value) {
            [$valueType, $expected] = $value;

            $this->assertEquals(Value::isEmpty($valueType, true), $expected);
        }
    }

    public function testIsEmptyNonStrictChecks()
    {
        $values = [
            ["", true],
            [[], true],
            [0, true],
            [0.00, true],
            ["0", true],
            ["0.00", false],
            [1, false]
        ];

        foreach ($values as $value) {
            [$valueType, $expected] = $value;

            $this->assertEquals(Value::isEmpty($valueType, false), $expected);
        }
    }
}