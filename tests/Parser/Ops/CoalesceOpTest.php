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
    public $opClass = CoalesceOp::class;

    public function testValidateErrorValues()
    {
        $this->assertValidated([
            'op_errors' => [
                1 => DummyFailOperation::error(),
                3 => DummyFailOperation::error()
            ]
        ], DummyFailOperation::op(), DummyOperation::op(), DummyFailOperation::op());
    }

    public function testEvaluate()
    {
        $this->assertEvaluated('first', DummyReturnOperation::op('first'), DummyReturnOperation::op('second'));
        $this->assertEvaluated('second', DummyReturnOperation::op(null), DummyReturnOperation::op('second'));
        $this->assertEvaluated(null, DummyReturnOperation::op(null), DummyReturnOperation::op(null));
    }


    public function testNonEvaluatedIfNotNeeded()
    {
        $this->assertEvaluated('first', DummyReturnOperation::op('first'), DummyCalledOperation::op('second'));
        $this->assertFalse(DummyCalledOperation::$evaluated);
        $this->assertEvaluated('second', DummyReturnOperation::op(null), DummyCalledOperation::op('second'));
        $this->assertTrue(DummyCalledOperation::$evaluated);
    }
}