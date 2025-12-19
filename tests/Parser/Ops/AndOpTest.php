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

use ArekX\RestFn\Parser\Ops\AndOp;
use tests\Parser\_mock\DummyCalledOperation;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyOperation;
use tests\Parser\_mock\DummyReturnOperation;

class AndOpTest extends OpTestCase
{
    public ?string $opClass = AndOp::class;

    public function testValidateEmptyValue()
    {
        $this->assertValidated(null);
    }

    public function testValidateSubItemsInAnd()
    {
        $this->assertValidated([
            'op_errors' => [1 => DummyFailOperation::error()]
        ], DummyFailOperation::op());
    }

    public function testValidateInBetweenAnd()
    {
        $this->assertValidated(
            [
                'op_errors' => [
                    1 => DummyFailOperation::error(),
                    3 => DummyFailOperation::error(),
                ]
            ],
            DummyFailOperation::op(),
            DummyOperation::op(),
            DummyFailOperation::op()
        );
    }

    public function testAllSucceed()
    {
        $this->assertValidated(
            null,
            DummyOperation::op(),
            DummyOperation::op(),
            DummyOperation::op(),
            DummyOperation::op(),
        );
    }

    public function testEvaluateEmpty()
    {
        $this->assertEvaluated(false);
    }

    public function testEvaluateTrue()
    {
        $this->assertEvaluated(true, DummyReturnOperation::op(true));
    }

    public function testFailFast()
    {
        $this->assertEvaluated(
            false,
            DummyReturnOperation::op(true),
            DummyReturnOperation::op(false),
            DummyCalledOperation::op(true),
        );

        DummyCalledOperation::$evaluated = false;
        $this->assertFalse(DummyCalledOperation::$evaluated);
    }

    public function testEvaluateAll()
    {
        $this->assertEvaluated(
            true,
            DummyReturnOperation::op(true),
            DummyReturnOperation::op(true),
            DummyCalledOperation::op(true),
        );

        $this->assertTrue(DummyCalledOperation::$evaluated);
    }
}
