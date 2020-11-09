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

use ArekX\RestFn\Parser\Ops\OrOp;
use ArekX\RestFn\Parser\Parser;
use tests\Parser\_mock\DummyCalledOperation;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyOperation;
use tests\Parser\_mock\DummyReturnOperation;
use tests\TestCase;

class OrOpTest extends TestCase
{
    public function testValidateEmptyValue()
    {
        $orOp = new OrOp();
        $this->assertEquals(null, $orOp->validate(new Parser(), [OrOp::name()]));
    }

    public function testValidateSubItemsInAnd()
    {
        $orOp = new OrOp();
        $parser = new Parser();
        $parser->ops = [
            DummyFailOperation::name() => DummyFailOperation::class
        ];
        $this->assertEquals([
            'op_errors' => [[DummyFailOperation::name(), ['failed' => true]]]
        ], $orOp->validate($parser, [
            OrOp::name(),
            [DummyFailOperation::name()],
        ]));
    }

    public function testValidateInBetweenAnd()
    {
        $orOp = new OrOp();
        $parser = new Parser();
        $parser->ops = [
            DummyFailOperation::name() => DummyFailOperation::class,
            DummyOperation::name() => DummyOperation::class
        ];
        $this->assertEquals([
            'op_errors' => [
                [DummyFailOperation::name(), ['failed' => true]],
                null,
                [DummyFailOperation::name(), ['failed' => true]],
            ]
        ], $orOp->validate($parser, [
            OrOp::name(),
            [DummyFailOperation::name()],
            [DummyOperation::name()],
            [DummyFailOperation::name()],
        ]));
    }

    public function testAllSucceed()
    {
        $orOp = new OrOp();
        $parser = new Parser();
        $parser->ops = [
            DummyFailOperation::name() => DummyFailOperation::class,
            DummyOperation::name() => DummyOperation::class
        ];
        $this->assertEquals(null, $orOp->validate($parser, [
            OrOp::name(),
            [DummyOperation::name()],
            [DummyOperation::name()],
            [DummyOperation::name()],
            [DummyOperation::name()],
        ]));
    }

    public function testEvaluateEmpty()
    {
        $orOp = new OrOp();
        $parser = new Parser();
        $this->assertEquals(false, $orOp->evaluate($parser, [OrOp::name()]));
    }

    public function testEvaluateTrue()
    {
        $orOp = new OrOp();
        $parser = new Parser();
        $parser->ops = [
            DummyReturnOperation::name() => DummyReturnOperation::class
        ];

        $this->assertEquals(true, $orOp->evaluate($parser, [OrOp::name(), [DummyReturnOperation::name(), true]]));
    }

    public function testSucceedFast()
    {
        $orOp = new OrOp();
        $parser = new Parser();
        $parser->ops = [
            DummyReturnOperation::name() => DummyReturnOperation::class,
            DummyCalledOperation::name() => DummyCalledOperation::class
        ];

        DummyCalledOperation::$evaluated = false;
        $this->assertEquals(true, $orOp->evaluate($parser, [OrOp::name(),
            [DummyReturnOperation::name(), true],
            [DummyReturnOperation::name(), false],
            [DummyCalledOperation::name(), false],
        ]));

        $this->assertFalse(DummyCalledOperation::$evaluated);
    }

    public function testEvaluateAll()
    {
        $orOp = new OrOp();
        $parser = new Parser();
        $parser->ops = [
            DummyReturnOperation::name() => DummyReturnOperation::class,
            DummyCalledOperation::name() => DummyCalledOperation::class
        ];

        DummyCalledOperation::$evaluated = false;
        $this->assertEquals(false, $orOp->evaluate($parser, [OrOp::name(),
            [DummyReturnOperation::name(), false],
            [DummyReturnOperation::name(), false],
            [DummyCalledOperation::name(), false],
        ]));

        $this->assertTrue(DummyCalledOperation::$evaluated);
    }
}