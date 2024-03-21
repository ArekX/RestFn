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

use ArekX\RestFn\Parser\Ops\ObjectOp;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyOperation;
use tests\Parser\_mock\DummyReturnOperation;

class ObjectOpTest extends OpTestCase
{
    public ?string $opClass = ObjectOp::class;

    public function testValidateParameters()
    {
        $this->assertValidated([
            'min_parameters' => 2,
            'max_parameters' => 2
        ]);
        $this->assertValidated(null, []);
        $this->assertValidated(null, ['key' => DummyOperation::op()]);
    }

    public function testValidateObject()
    {
        $error = [
            'invalid_object_expression' => ['name' => DummyFailOperation::error()]
        ];

        $this->assertValidated($error, ['name' => DummyFailOperation::op()]);
        $this->assertValidated(null, ['name' => DummyOperation::op()]);
    }

    public function testEvaluate()
    {
        $this->assertEvaluated([
            'name' => 'test',
            'age' => 22
        ], [
            'name' => DummyReturnOperation::op('test'),
            'age' => DummyReturnOperation::op(22)
        ]);

        $this->assertEvaluated([], []);
    }
}
