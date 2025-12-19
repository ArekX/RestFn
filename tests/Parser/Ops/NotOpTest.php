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

use ArekX\RestFn\Parser\Ops\NotOp;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyOperation;
use tests\Parser\_mock\DummyReturnOperation;

class NotOpTest extends OpTestCase
{
    public ?string $opClass = NotOp::class;

    public function testValidateEmptyValue()
    {
        $this->assertValidated([
            'min_parameters' => 2,
            'max_parameters' => 2
        ]);
    }

    public function testValidateOneFailingExpression()
    {
        $this->assertValidated(['op_error' => DummyFailOperation::error()], DummyFailOperation::op());
    }

    public function testValidateOneSuccessfulExpression()
    {
        $this->assertValidated(null, DummyOperation::op());
    }

    public function testEvaluate()
    {
        $this->assertEvaluated(false, DummyReturnOperation::op(true));
        $this->assertEvaluated(true, DummyReturnOperation::op(false));
    }
}
