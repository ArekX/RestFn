<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
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