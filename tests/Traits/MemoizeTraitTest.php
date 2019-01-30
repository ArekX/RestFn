<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Traits;


use tests\TestCase;
use tests\Traits\Handlers\MemoizeHandler;

class MemoizeTraitTest extends TestCase
{
    public function testMemoizedFunctionIsNotCalculatedTwice()
    {
        $ob = new MemoizeHandler();
        $value = $ob->memoizeMethod();
        $this->assertEquals($value, $ob->memoizeMethod());
    }

    public function testStaticMemoizedFunctionIsNotCalculatedTwice()
    {
        $value = MemoizeHandler::memoizeStaticMethod();
        $this->assertEquals($value, MemoizeHandler::memoizeStaticMethod());
    }
}