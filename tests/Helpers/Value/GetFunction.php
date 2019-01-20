<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Helpers\Value;


use ArekX\JsonQL\Helpers\Value;

trait GetFunction
{
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
}