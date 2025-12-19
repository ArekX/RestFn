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

use ArekX\RestFn\Parser\Ops\CompareOp;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyOperation;
use tests\Parser\_mock\DummyReturnOperation;

class CompareOpTest extends OpTestCase
{
    public ?string $opClass = CompareOp::class;

    public function testValidateParameters()
    {
        $error = ['min_parameters' => 4, 'max_parameters' => 4];

        $this->assertValidated($error, DummyOperation::op());
        $this->assertValidated($error, DummyOperation::op(), '=');
        $this->assertValidated(null, DummyOperation::op(), '=', DummyOperation::op());
    }

    public function testValidateOperation()
    {
        $this->assertValidated(null, DummyOperation::op(), DummyOperation::op(), DummyOperation::op());

        $this->assertValidated([
            'invalid_operation_expression' => DummyFailOperation::error()
        ], DummyOperation::op(), DummyFailOperation::op(), DummyOperation::op());

        $this->assertValidated([
            'invalid_operation' => 'a'
        ], DummyOperation::op(), 'a', DummyOperation::op());

        $this->assertValidated([
            'invalid_operation' => '*'
        ], DummyOperation::op(), '*', DummyOperation::op());

        $this->assertValidated(null, DummyOperation::op(), '=', DummyOperation::op());
        $this->assertValidated(null, DummyOperation::op(), '==', DummyOperation::op());
        $this->assertValidated(null, DummyOperation::op(), '!=', DummyOperation::op());
        $this->assertValidated(null, DummyOperation::op(), '!==', DummyOperation::op());
        $this->assertValidated(null, DummyOperation::op(), '>', DummyOperation::op());
        $this->assertValidated(null, DummyOperation::op(), '<', DummyOperation::op());
        $this->assertValidated(null, DummyOperation::op(), '<=', DummyOperation::op());
        $this->assertValidated(null, DummyOperation::op(), '>=', DummyOperation::op());
    }

    public function testFailedExpressions()
    {
        $this->assertValidated([
            'invalid_left_expression' => DummyFailOperation::error()
        ], DummyFailOperation::op(), '=', DummyOperation::op());

        $this->assertValidated([
            'invalid_right_expression' => DummyFailOperation::error()
        ], DummyOperation::op(), '=', DummyFailOperation::op());
    }

    public function testEvaluate()
    {
        $this->assertEvaluated(true, DummyReturnOperation::op(1), '=', DummyReturnOperation::op(1));
        $this->assertEvaluated(false, DummyReturnOperation::op(1), '=', DummyReturnOperation::op(2));

        $this->assertEvaluated(true, DummyReturnOperation::op(1), '==', DummyReturnOperation::op(1));
        $this->assertEvaluated(false, DummyReturnOperation::op(1), '==', DummyReturnOperation::op(')'));

        $this->assertEvaluated(false, DummyReturnOperation::op(1), '!=', DummyReturnOperation::op(1));
        $this->assertEvaluated(true, DummyReturnOperation::op(1), '!=', DummyReturnOperation::op(2));

        $this->assertEvaluated(false, DummyReturnOperation::op(1), '!==', DummyReturnOperation::op(1));
        $this->assertEvaluated(true, DummyReturnOperation::op(1), '!==', DummyReturnOperation::op(')'));

        $this->assertEvaluated(true, DummyReturnOperation::op(2), '>', DummyReturnOperation::op(1));
        $this->assertEvaluated(false, DummyReturnOperation::op(1), '>', DummyReturnOperation::op(2));

        $this->assertEvaluated(true, DummyReturnOperation::op(1), '<', DummyReturnOperation::op(2));
        $this->assertEvaluated(false, DummyReturnOperation::op(2), '<', DummyReturnOperation::op(1));

        $this->assertEvaluated(true, DummyReturnOperation::op(1), '<=', DummyReturnOperation::op(2));
        $this->assertEvaluated(true, DummyReturnOperation::op(2), '<=', DummyReturnOperation::op(2));
        $this->assertEvaluated(false, DummyReturnOperation::op(2), '<=', DummyReturnOperation::op(1));

        $this->assertEvaluated(true, DummyReturnOperation::op(2), '>=', DummyReturnOperation::op(1));
        $this->assertEvaluated(true, DummyReturnOperation::op(2), '>=', DummyReturnOperation::op(2));
        $this->assertEvaluated(false, DummyReturnOperation::op(1), '>=', DummyReturnOperation::op(2));

        $this->assertEvaluated(false, DummyReturnOperation::op(1), '*', DummyReturnOperation::op(2));
        $this->assertEvaluated(false, DummyReturnOperation::op(1), '/', DummyReturnOperation::op(2));
        $this->assertEvaluated(false, DummyReturnOperation::op(1), '+', DummyReturnOperation::op(2));
        $this->assertEvaluated(false, DummyReturnOperation::op(1), '-', DummyReturnOperation::op(2));
    }
}
