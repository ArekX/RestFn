<?php

/**
 * Copyright 2025 Aleksandar Panic
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

use ArekX\RestFn\Parser\Ops\SequenceOp;
use tests\Parser\_mock\DummyCalledOperation;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyOperation;
use tests\Parser\_mock\DummyReturnOperation;

class SequenceOpTest extends OpTestCase
{
    public ?string $opClass = SequenceOp::class;

    public function testValidateEmptyValue()
    {
        $this->assertValidated(null);
    }


    public function testValidateSubItems()
    {
        $this->assertValidated(null, DummyOperation::op(), DummyOperation::op());
        $this->assertValidated([
            'op_errors' => [
                2 => DummyFailOperation::error()
            ]
        ], DummyOperation::op(), DummyFailOperation::op());
    }

    public function testEvaluate()
    {
        $this->assertEvaluated(null);
        $this->assertEvaluated(1, DummyReturnOperation::op(1));
        $this->assertEvaluated(2, DummyReturnOperation::op(1), DummyReturnOperation::op(2));
    }

    public function testAllEvaluated()
    {
        $this->assertEvaluated(3, DummyReturnOperation::op(1), DummyCalledOperation::op(2), DummyReturnOperation::op(3));
        $this->assertTrue(DummyCalledOperation::$evaluated);
    }
}
