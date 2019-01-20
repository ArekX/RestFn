<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Helpers\Value;


use ArekX\JsonQL\Helpers\Value;

trait MergeFunction
{
    public function testMergeEmpty()
    {
        $result = Value::merge();
        $this->assertEmpty($result);
    }

    public function testMergeTwo()
    {
        $result = Value::merge(['a' => 1], ['b' => 1]);

        $this->assertEquals([
            'a' => 1,
            'b' => 1,
        ], $result);
    }

    public function testMergeThree()
    {
        $result = Value::merge(['a' => 1], ['b' => 1], ['c' => 1]);

        $this->assertEquals([
            'a' => 1,
            'b' => 1,
            'c' => 1
        ], $result);
    }

    public function testMergeOverride()
    {
        $result = Value::merge(['a' => 1], ['b' => 1], ['b' => 2]);

        $this->assertEquals([
            'a' => 1,
            'b' => 2,
        ], $result);
    }

    public function testRecursiveMerge()
    {
        $result = Value::merge([
            'a' => true,
            'b' => [
                'c' => 1,
                'd' => 'TEST'
            ]
        ], [
            'b' => [
                'e' => 1
            ]
        ]);

        $this->assertEquals([
            'a' => true,
            'b' => [
                'c' => 1,
                'd' => 'TEST',
                'e' => 1
            ],
        ], $result);
    }

    public function testRecursiveOverride()
    {
        $result = Value::merge([
            'a' => true,
            'b' => [
                'c' => 1,
                'd' => ['e' => true]
            ]
        ], [
            'b' => [
                'd' => 'e'
            ]
        ]);

        $this->assertEquals([
            'a' => true,
            'b' => [
                'c' => 1,
                'd' => 'e',
            ],
        ], $result);
    }
}