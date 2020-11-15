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

use ArekX\RestFn\Parser\Ops\CoalesceOp;
use tests\Parser\_mock\DummyCalledOperation;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyOperation;
use tests\Parser\_mock\DummyReturnOperation;

class CoalesceOpTest extends OpTestCase
{
    public function testValidateErrorValues()
    {
        $op = new CoalesceOp();
        $this->assertEquals([
            'op_errors' => [
                1 => DummyFailOperation::errorValue(),
                3 => DummyFailOperation::errorValue()
            ]
        ], $op->validate($this->getParser([DummyFailOperation::class, DummyOperation::class]), [CoalesceOp::name(),
            [DummyFailOperation::name()],
            [DummyOperation::name()],
            [DummyFailOperation::name()]
        ]));
    }

    public function testEvaluate()
    {
        $this->assertResult('first', [DummyReturnOperation::name(), 'first'], [DummyReturnOperation::name(), 'second']);
        $this->assertResult('second', [DummyReturnOperation::name(), null], [DummyReturnOperation::name(), 'second']);
        $this->assertResult(null, [DummyReturnOperation::name(), null], [DummyReturnOperation::name(), null]);
    }


    public function testNonEvaluatedIfNotNeeded()
    {
        $this->assertResult('first', [DummyReturnOperation::name(), 'first'], [DummyCalledOperation::name(), 'second']);
        $this->assertFalse(DummyCalledOperation::$evaluated);
        $this->assertResult('second', [DummyReturnOperation::name(), null], [DummyCalledOperation::name(), 'second']);
        $this->assertTrue(DummyCalledOperation::$evaluated);
    }

    public function assertResult($expectedResult, ...$values)
    {
        $op = new CoalesceOp();
        $parser = $this->getParser([DummyReturnOperation::class, DummyCalledOperation::class]);

        DummyCalledOperation::$evaluated = false;
        $this->assertEquals($expectedResult, $op->evaluate($parser, [CoalesceOp::name(), ...$values]));
    }
}