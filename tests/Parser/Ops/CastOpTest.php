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

use ArekX\RestFn\Parser\Ops\CastOp;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyOperation;
use tests\Parser\_mock\DummyReturnOperation;

class CastOpTest extends OpTestCase
{
    public ?string $opClass = CastOp::class;

    public function testValidateEmptyValue()
    {
        $error = [
            'min_parameters' => 3,
            'max_parameters' => 3
        ];

        $this->assertValidated($error);
        $this->assertValidated($error, DummyOperation::op());

        $this->assertValidated(null, DummyOperation::op(), DummyOperation::op());
    }

    public function testValidateType()
    {
        $this->assertValidated(null, 'bool', DummyOperation::op());
        $this->assertValidated(null, 'int', DummyOperation::op());
        $this->assertValidated(null, 'string', DummyOperation::op());
        $this->assertValidated(null, 'float', DummyOperation::op());
        $this->assertValidated([
            'invalid_type_value' => 'double'
        ], 'double', DummyOperation::op());

        $this->assertValidated([
            'invalid_type_expression' => DummyFailOperation::error()
        ], DummyFailOperation::op(), DummyOperation::op());
    }

    public function testValidateFrom()
    {
        $this->assertValidated(null, 'bool', DummyOperation::op());
        $this->assertValidated(['invalid_value_expression' => DummyFailOperation::error()], 'bool', DummyFailOperation::op());
    }

    public function testEvaluate()
    {
        $this->assertEvaluated(true, 'bool', DummyReturnOperation::op(1));
        $this->assertEvaluated(true, DummyReturnOperation::op('bool'), DummyReturnOperation::op(1));
        $this->assertEvaluated(false, 'bool', DummyReturnOperation::op(0));

        $this->assertEvaluated(1.52, 'float', DummyReturnOperation::op('1.52'));
        $this->assertEvaluated(1, 'float', DummyReturnOperation::op(1));
        $this->assertEvaluated(1, 'float', DummyReturnOperation::op(true));
        $this->assertEvaluated(0, 'float', DummyReturnOperation::op(false));

        $this->assertEvaluated(1, 'int', DummyReturnOperation::op('1'));
        $this->assertEvaluated(1, 'int', DummyReturnOperation::op(1.5));
        $this->assertEvaluated(1, 'int', DummyReturnOperation::op(true));
        $this->assertEvaluated(0, 'int', DummyReturnOperation::op(false));

        $this->assertEvaluated('', 'string', DummyReturnOperation::op(false));
        $this->assertEvaluated('1', 'string', DummyReturnOperation::op(true));
        $this->assertEvaluated('1.55', DummyReturnOperation::op('string'), DummyReturnOperation::op(1.55));
        $this->assertEvaluated('1', 'string', DummyReturnOperation::op(1));
    }

    public function testEvaluateInvalid()
    {
        $this->expectException(\Exception::class);
        $this->assertEvaluated(null, 'invalid type', DummyReturnOperation::op(1));
    }
}
