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

use ArekX\RestFn\Parser\Ops\CompareOp;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyOperation;
use tests\Parser\_mock\DummyReturnOperation;

class CompareOpTest extends OpTestCase
{
    public function testValidateParameters()
    {
        $op = new CompareOp();
        $parser = $this->getParser([DummyOperation::class]);
        $dummyOp = [DummyOperation::name()];
        $this->assertEquals([
            'min_parameters' => 4,
            'max_parameters' => 4
        ], $op->validate($parser, [CompareOp::name()]));

        $this->assertEquals([
            'min_parameters' => 4,
            'max_parameters' => 4
        ], $op->validate($parser, [CompareOp::name(), $dummyOp]));

        $this->assertEquals([
            'min_parameters' => 4,
            'max_parameters' => 4
        ], $op->validate($parser, [CompareOp::name(), $dummyOp, '=']));

        $this->assertEquals(null, $op->validate($parser, [CompareOp::name(), $dummyOp, '=', $dummyOp]));
    }

    public function testValidateOperation()
    {
        $op = new CompareOp();
        $parser = $this->getParser([DummyOperation::class]);
        $dummyOp = [DummyOperation::name()];

        $this->assertEquals([
            'invalid_operation' => []
        ], $op->validate($parser, [CompareOp::name(), $dummyOp, [], $dummyOp]));

        $this->assertEquals([
            'invalid_operation' => 'a'
        ], $op->validate($parser, [CompareOp::name(), $dummyOp, 'a', $dummyOp]));

        $this->assertEquals([
            'invalid_operation' => '*'
        ], $op->validate($parser, [CompareOp::name(), $dummyOp, '*', $dummyOp]));

        $this->assertEquals(null, $op->validate($parser, [CompareOp::name(), $dummyOp, '=', $dummyOp]));
        $this->assertEquals(null, $op->validate($parser, [CompareOp::name(), $dummyOp, '!=', $dummyOp]));
        $this->assertEquals(null, $op->validate($parser, [CompareOp::name(), $dummyOp, '>', $dummyOp]));
        $this->assertEquals(null, $op->validate($parser, [CompareOp::name(), $dummyOp, '<', $dummyOp]));
        $this->assertEquals(null, $op->validate($parser, [CompareOp::name(), $dummyOp, '<=', $dummyOp]));
        $this->assertEquals(null, $op->validate($parser, [CompareOp::name(), $dummyOp, '>=', $dummyOp]));
    }

    public function testFailedExpressions()
    {
        $op = new CompareOp();
        $parser = $this->getParser([DummyOperation::class, DummyFailOperation::class]);


        $this->assertEquals([
            'invalid_left_expression' => [DummyFailOperation::name(), ['failed' => true]]
        ], $op->validate($parser, [CompareOp::name(), [DummyFailOperation::name()], '=', [DummyOperation::name()]]));


        $this->assertEquals([
            'invalid_right_expression' => [DummyFailOperation::name(), ['failed' => true]]
        ], $op->validate($parser, [CompareOp::name(), [DummyOperation::name()], '=', [DummyFailOperation::name()]]));
    }

    public function testEvaluate()
    {
        $this->assertCompareResult(true, [DummyReturnOperation::name(), 1], '=', [DummyReturnOperation::name(), 1]);
        $this->assertCompareResult(false, [DummyReturnOperation::name(), 1], '=', [DummyReturnOperation::name(), 2]);

        $this->assertCompareResult(false, [DummyReturnOperation::name(), 1], '!=', [DummyReturnOperation::name(), 1]);
        $this->assertCompareResult(true, [DummyReturnOperation::name(), 1], '!=', [DummyReturnOperation::name(), 2]);

        $this->assertCompareResult(true, [DummyReturnOperation::name(), 2], '>', [DummyReturnOperation::name(), 1]);
        $this->assertCompareResult(false, [DummyReturnOperation::name(), 1], '>', [DummyReturnOperation::name(), 2]);

        $this->assertCompareResult(true, [DummyReturnOperation::name(), 1], '<', [DummyReturnOperation::name(), 2]);
        $this->assertCompareResult(false, [DummyReturnOperation::name(), 2], '<', [DummyReturnOperation::name(), 1]);

        $this->assertCompareResult(true, [DummyReturnOperation::name(), 1], '<=', [DummyReturnOperation::name(), 2]);
        $this->assertCompareResult(true, [DummyReturnOperation::name(), 2], '<=', [DummyReturnOperation::name(), 2]);
        $this->assertCompareResult(false, [DummyReturnOperation::name(), 2], '<=', [DummyReturnOperation::name(), 1]);

        $this->assertCompareResult(true, [DummyReturnOperation::name(), 2], '>=', [DummyReturnOperation::name(), 1]);
        $this->assertCompareResult(true, [DummyReturnOperation::name(), 2], '>=', [DummyReturnOperation::name(), 2]);
        $this->assertCompareResult(false, [DummyReturnOperation::name(), 1], '>=', [DummyReturnOperation::name(), 2]);

        $this->assertCompareResult(false, [DummyReturnOperation::name(), 1], '*', [DummyReturnOperation::name(), 2]);
        $this->assertCompareResult(false, [DummyReturnOperation::name(), 1], '/', [DummyReturnOperation::name(), 2]);
        $this->assertCompareResult(false, [DummyReturnOperation::name(), 1], '+', [DummyReturnOperation::name(), 2]);
        $this->assertCompareResult(false, [DummyReturnOperation::name(), 1], '-', [DummyReturnOperation::name(), 2]);
    }

    protected function assertCompareResult($expectedResult, $a, $opValue, $b)
    {
        $op = new CompareOp();
        $parser = $this->getParser([
            DummyReturnOperation::class
        ]);

        $this->assertEquals($expectedResult, $op->evaluate($parser, [CompareOp::name(), $a, $opValue, $b]));
    }
}