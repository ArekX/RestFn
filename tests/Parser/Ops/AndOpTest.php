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

use ArekX\RestFn\Parser\Ops\AndOp;
use ArekX\RestFn\Parser\Parser;
use tests\Parser\_mock\DummyCalledOperation;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyOperation;
use tests\Parser\_mock\DummyReturnOperation;
use tests\TestCase;

class AndOpTest extends OpTestCase
{
    public function testValidateEmptyValue()
    {
        $andOp = new AndOp();
        $this->assertEquals(null, $andOp->validate($this->getParser(), [AndOp::name()]));
    }

    public function testValidateSubItemsInAnd()
    {
        $andOp = new AndOp();
        $parser = $this->getParser([
            DummyFailOperation::class
        ]);

        $this->assertEquals([
            'op_errors' => [1 => DummyFailOperation::errorValue()]
        ], $andOp->validate($parser, [
            AndOp::name(),
            [DummyFailOperation::name()],
        ]));
    }

    public function testValidateInBetweenAnd()
    {
        $andOp = new AndOp();
        $parser = $this->getParser([
            DummyFailOperation::class,
            DummyOperation::class
        ]);

        $this->assertEquals([
            'op_errors' => [
                1 => DummyFailOperation::errorValue(),
                3 => DummyFailOperation::errorValue(),
            ]
        ], $andOp->validate($parser, [
            AndOp::name(),
            [DummyFailOperation::name()],
            [DummyOperation::name()],
            [DummyFailOperation::name()],
        ]));
    }

    public function testAllSucceed()
    {
        $andOp = new AndOp();
        $parser = $this->getParser([
            DummyFailOperation::class,
            DummyOperation::class
        ]);

        $this->assertEquals(null, $andOp->validate($parser, [
            AndOp::name(),
            [DummyOperation::name()],
            [DummyOperation::name()],
            [DummyOperation::name()],
            [DummyOperation::name()],
        ]));
    }

    public function testEvaluateEmpty()
    {
        $andOp = new AndOp();
        $parser = $this->getParser();
        $this->assertEquals(false, $andOp->evaluate($parser, [AndOp::name()]));
    }

    public function testEvaluateTrue()
    {
        $andOp = new AndOp();
        $parser = $this->getParser([DummyReturnOperation::class]);

        $this->assertEquals(true, $andOp->evaluate($parser, [AndOp::name(), [DummyReturnOperation::name(), true]]));
    }

    public function testFailFast()
    {
        $andOp = new AndOp();
        $parser = $this->getParser([
            DummyReturnOperation::class,
            DummyCalledOperation::class
        ]);

        DummyCalledOperation::$evaluated = false;
        $this->assertEquals(false, $andOp->evaluate($parser, [AndOp::name(),
            [DummyReturnOperation::name(), true],
            [DummyReturnOperation::name(), false],
            [DummyCalledOperation::name(), true],
        ]));

        $this->assertFalse(DummyCalledOperation::$evaluated);
    }

    public function testEvaluateAll()
    {
        $andOp = new AndOp();
        $parser = $this->getParser([
            DummyReturnOperation::class,
            DummyCalledOperation::class
        ]);

        DummyCalledOperation::$evaluated = false;
        $this->assertEquals(true, $andOp->evaluate($parser, [AndOp::name(),
            [DummyReturnOperation::name(), true],
            [DummyReturnOperation::name(), true],
            [DummyCalledOperation::name(), true],
        ]));

        $this->assertTrue(DummyCalledOperation::$evaluated);
    }
}