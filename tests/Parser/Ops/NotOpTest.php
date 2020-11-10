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

use ArekX\RestFn\Parser\Ops\NotOp;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyOperation;
use tests\Parser\_mock\DummyReturnOperation;

class NotOpTest extends OpTestCase
{
    public function testValidateEmptyValue()
    {
        $op = new NotOp();
        $this->assertEquals([
            'min_parameters' => 1,
            'max_parameters' => 1
        ], $op->validate($this->getParser(), [NotOp::name()]));
    }

    public function testValidateOneFailingExpression()
    {
        $op = new NotOp();
        $parser = $this->getParser([DummyFailOperation::class]);

        $this->assertEquals([
            'op_error' => [DummyFailOperation::name(), ['failed' => true]]
        ], $op->validate($parser, [NotOp::name(), [DummyFailOperation::name()]]));
    }

    public function testValidateOneSuccessfulExpression()
    {
        $op = new NotOp();
        $parser = $this->getParser([DummyOperation::class]);

        $this->assertEquals(null, $op->validate($parser, [NotOp::name(), [DummyOperation::name()]]));
    }

    public function testEvaluateTruthy()
    {
        $op = new NotOp();
        $parser = $this->getParser([DummyReturnOperation::class]);

        $this->assertSame(false, $op->evaluate($parser, [NotOp::name(), [DummyReturnOperation::name(), true]]));
    }

    public function testEvaluateFalsy()
    {
        $op = new NotOp();
        $parser = $this->getParser([DummyReturnOperation::class]);

        $this->assertSame(true, $op->evaluate($parser, [NotOp::name(), [DummyReturnOperation::name(), false]]));
    }
}