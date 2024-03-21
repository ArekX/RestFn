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

use ArekX\RestFn\Parser\Ops\IfElseOp;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyOperation;
use tests\Parser\_mock\DummyReturnOperation;

class IfElseOpTest extends OpTestCase
{
    public ?string $opClass = IfElseOp::class;

    public function testValidate()
    {
        $error = ['min_parameters' => 4, 'max_parameters' => 4];

        $this->assertValidated($error);
        $this->assertValidated($error, DummyOperation::op());
        $this->assertValidated($error, DummyOperation::op(), DummyOperation::op());
        $this->assertValidated(null, DummyOperation::op(), DummyOperation::op(), DummyOperation::op());
    }

    public function testSubParamValidate()
    {
        $this->assertValidated([
            'if_expression_invalid' => DummyFailOperation::error()
        ], DummyFailOperation::op(), DummyOperation::op(), DummyOperation::op());

        $this->assertValidated([
            'true_expression_invalid' => DummyFailOperation::error()
        ], DummyOperation::op(), DummyFailOperation::op(), DummyOperation::op());

        $this->assertValidated([
            'false_expression_invalid' => DummyFailOperation::error()
        ], DummyOperation::op(), DummyOperation::op(), DummyFailOperation::op());
    }

    public function testEvaluate()
    {
        $this->assertEvaluated('trueValue', DummyReturnOperation::op(true), DummyReturnOperation::op('trueValue'), DummyReturnOperation::op('falseValue'));
        $this->assertEvaluated('falseValue', DummyReturnOperation::op(false), DummyReturnOperation::op('trueValue'), DummyReturnOperation::op('falseValue'));
    }
}
