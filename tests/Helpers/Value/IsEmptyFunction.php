<?php
/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
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

            $this->assertEquals($expected, Value::isEmpty($valueType, true));
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

            $this->assertEquals($expected, Value::isEmpty($valueType, false));
        }
    }
}