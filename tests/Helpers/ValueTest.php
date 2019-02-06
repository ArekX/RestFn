<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\Helpers;

use ArekX\JsonQL\Helpers\Value;
use tests\TestCase;

class ValueTest extends TestCase
{
    public function testSetupValuesWithEmptyConfig()
    {
        $ob = new \stdClass();

        Value::setup($ob, [], [
            'test' => 'default'
        ]);

        $this->assertEquals('default', $ob->test);
    }

    public function testSetupValuesWithExistingConfig()
    {
        $ob = new \stdClass();

        Value::setup($ob, ['test' => 'existing'], [
            'test' => 'default'
        ]);

        $this->assertEquals('existing', $ob->test);
    }

    public function testGetFromEmptyObject()
    {
        $result = Value::get(null, 'test');
        $this->assertEquals(null, $result);
    }

    public function testGetFromStdClass()
    {
        $ob = new \stdClass();
        $ob->test = 2;
        $result = Value::get($ob, 'test');
        $this->assertEquals(2, $result);
    }

    public function testGetFromArray()
    {
        $ob = ['test' => 2];
        $result = Value::get($ob, 'test');
        $this->assertEquals(2, $result);
    }

    public function testGetFromArrayTraversal()
    {
        $ob = ['test' => ['a' => ['b' => 'value']]];
        $result = Value::get($ob, 'test.a.b');
        $this->assertEquals('value', $result);
    }


    public function testGetValueFromDotNotationArray()
    {
        $ob = ['test.a.b.c' => 'value'];
        $result = Value::get($ob, 'test.a.b.c');
        $this->assertEquals('value', $result);
    }

    public function testTraversalToNonExistingValue()
    {
        $ob = ['test' => ['a' => ['b' => 'value']]];
        $result = Value::get($ob, 'test.a.b.d', 'default value');
        $this->assertEquals('default value', $result);
    }

    public function testGetFromObjectTraversal()
    {
        $ob = new \stdClass();
        $ob->test = new \stdClass();
        $ob->test->a = new \stdClass();
        $ob->test->a->b = new \stdClass();
        $ob->test->a->b->c = 'value';
        $result = Value::get($ob, 'test.a.b.c');
        $this->assertEquals('value', $result);
    }

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