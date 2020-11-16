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

use ArekX\RestFn\Parser\Ops\MergeOp;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyOperation;
use tests\Parser\_mock\DummyReturnOperation;

class MergeOpTest extends OpTestCase
{
    public $opClass = MergeOp::class;

    public function testValidate()
    {
        $this->assertValidated(['min_parameters' => 2]);
        $this->assertValidated([
            'op_errors' => [
                1 => DummyFailOperation::error()
            ]
        ], DummyFailOperation::op());

        $this->assertValidated(null, [DummyOperation::name()]);
    }


    public function testEvaluate()
    {
        $this->assertEvaluated([
            'test1' => 'value1',
            'test2' => 'value2'
        ], DummyReturnOperation::op(['test1' => 'value1']), DummyReturnOperation::op(['test2' => 'value2']));
    }

    public function testOverride()
    {
        $this->assertEvaluated([
            'test1' => 'value2',
            'test3' => 'value3'
        ], DummyReturnOperation::op(['test1' => 'value1', 'test3' => 'value3']), DummyReturnOperation::op(['test1' => 'value2']));
    }

    public function testOneEmptyArray()
    {
        $this->assertEvaluated([
            'test1' => 'value2',
        ], DummyReturnOperation::op([]), DummyReturnOperation::op(['test1' => 'value2']));
    }

    public function testJoinArrays()
    {
        $this->assertEvaluated([
            1, 2, 3, 4, 1, 2, 3, 4, 5
        ], DummyReturnOperation::op([1, 2, 3, 4]), DummyReturnOperation::op([1, 2, 3, 4, 5]));
    }

    public function testAllEmpty()
    {
        $this->assertEvaluated([], DummyReturnOperation::op([]), DummyReturnOperation::op([]));
    }
}