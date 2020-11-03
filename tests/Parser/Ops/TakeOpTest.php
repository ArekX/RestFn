<?php
/**
 * Copyright 2020 Aleksandar Panic
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 **/

namespace tests\Parser\Ops;

use ArekX\RestFn\Parser\Ops\TakeOp;
use ArekX\RestFn\Parser\Parser;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyOperation;
use tests\Parser\_mock\DummyReturnOperation;
use tests\TestCase;

class TakeOpTest extends TestCase
{
    public function testParameterValidation()
    {
        $takeOp = new TakeOp();

        $this->assertEquals(['min_parameters' => 2], $takeOp->validate($this->createParser(), []));
        $this->assertEquals(['min_parameters' => 2], $takeOp->validate($this->createParser(), [TakeOp::name()]));
        $this->assertEquals(['min_parameters' => 2], $takeOp->validate($this->createParser(), [TakeOp::name(), 1]));
        $this->assertEquals(null, $takeOp->validate($this->createParser(), [TakeOp::name(), 1, [ DummyOperation::name()]]));
    }

    public function testNonNumericAmount()
    {
        $takeOp = new TakeOp();

        $this->assertEquals(['invalid_amount' => 'not_a_number'], $takeOp->validate(
            $this->createParser(),
            [TakeOp::name(), 'not_a_number', [DummyFailOperation::name()]])
        );

        $this->assertEquals(['invalid_amount' => false], $takeOp->validate(
            $this->createParser(),
            [TakeOp::name(), false, [DummyFailOperation::name()]])
        );
    }

    public function testValueValid()
    {
        $takeOp = new TakeOp();

        $this->assertEquals(['value_error' => [DummyFailOperation::name(), ['failed' => true]]], $takeOp->validate(
            $this->createParser(),
            [TakeOp::name(), 1, [DummyFailOperation::name()]])
        );
    }


    public function testTake()
    {
        $takeOp = new TakeOp();

        $this->assertEquals([1, 22], $takeOp->evaluate(
            $this->createParser(),
            [TakeOp::name(), 2, [DummyReturnOperation::name(), [1,22,3,456]]])
        );
    }

    public function testTakeNone()
    {
        $takeOp = new TakeOp();

        $this->assertEquals([], $takeOp->evaluate(
            $this->createParser(),
            [TakeOp::name(), 0, [DummyReturnOperation::name(), [1,22,3,456]]])
        );
    }

    public function testTakeRight()
    {
        $takeOp = new TakeOp();

        $this->assertEquals([3, 456], $takeOp->evaluate(
            $this->createParser(),
            [TakeOp::name(), -2, [DummyReturnOperation::name(), [1,22,3,456]]])
        );
    }

    public function testTakeMore()
    {
        $takeOp = new TakeOp();

        $this->assertEquals([1, 2, 3], $takeOp->evaluate(
            $this->createParser(),
            [TakeOp::name(), 50, [DummyReturnOperation::name(), [1,2,3]]])
        );
    }

    public function testException()
    {
        $takeOp = new TakeOp();

        $this->expectException(\Exception::class);

        $takeOp->evaluate(
            $this->createParser(),
            [TakeOp::name(), 2, [DummyReturnOperation::name(), null]]
        );
    }

    protected function createParser()
    {
        $parser = new Parser();
        $parser->ops = [
            DummyOperation::name() => DummyOperation::class,
            DummyFailOperation::name() => DummyFailOperation::class,
            DummyReturnOperation::name() => DummyReturnOperation::class
        ];

        return $parser;
    }
}