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
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyOperation;
use tests\Parser\_mock\DummyReturnOperation;

class TakeOpTest extends OpTestCase
{
    const TEST_ARRAY = [1, 22, 3, 456];

    public ?string $opClass = TakeOp::class;

    public function testParameterValidation()
    {
        $parameterError = ['min_parameters' => 3, 'max_parameters' => 3];

        $this->assertValidated($parameterError);
        $this->assertValidated($parameterError, 1);
        $this->assertValidated(null, 1, DummyOperation::op());
    }

    public function testNonNumericAmount()
    {
        $this->assertValidated(['invalid_amount' => 'not_a_number'], 'not_a_number', DummyOperation::op());
        $this->assertValidated(['invalid_amount' => false], false, DummyOperation::op());
        $this->assertValidated(['invalid_amount' => true], true, DummyOperation::op());
        $this->assertValidated(['invalid_amount' => null], null, DummyOperation::op());
    }

    public function testValueExpressionValid()
    {
        $this->assertValidated(null, DummyOperation::op(), DummyOperation::op());
        $this->assertValidated([
            'invalid_amount_expression' => DummyFailOperation::error()
        ], DummyFailOperation::op(), DummyOperation::op());
    }

    public function testValueValid()
    {
        $this->assertValidated(['value_error' => DummyFailOperation::error()], 1, DummyFailOperation::op());
    }

    public function testTake()
    {
        $this->assertEvaluated([1, 22], 2, DummyReturnOperation::op(self::TEST_ARRAY));
        $this->assertEvaluated([1, 22], DummyReturnOperation::op(2), DummyReturnOperation::op(self::TEST_ARRAY));
        $this->assertEvaluated([], DummyReturnOperation::op(0), DummyReturnOperation::op(self::TEST_ARRAY));
    }

    public function testTakeRight()
    {
        $this->assertEvaluated([3, 456], -2, DummyReturnOperation::op(self::TEST_ARRAY));
    }

    public function testTakeMore()
    {
        $this->assertEvaluated(self::TEST_ARRAY, 50, DummyReturnOperation::op(self::TEST_ARRAY));
    }

    public function testException()
    {
        $this->expectException(\Exception::class);
        $this->assertEvaluated(null, 2, DummyReturnOperation::op(null));
    }
}
