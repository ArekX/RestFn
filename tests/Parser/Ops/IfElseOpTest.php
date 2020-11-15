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
    public function testValidate()
    {
        $op = new IfElseOp();
        $parser = $this->getParser([DummyOperation::class]);
        $this->assertEquals([
            'min_parameters' => 4,
            'max_parameters' => 4
        ], $op->validate($parser, []));


        $this->assertEquals([
            'min_parameters' => 4,
            'max_parameters' => 4
        ], $op->validate($parser, [IfElseOp::name()]));

        $this->assertEquals([
            'min_parameters' => 4,
            'max_parameters' => 4
        ], $op->validate($parser, [IfElseOp::name(), [DummyOperation::name()]]));

        $this->assertEquals([
            'min_parameters' => 4,
            'max_parameters' => 4
        ], $op->validate($parser, [IfElseOp::name(), [DummyOperation::name()], [DummyOperation::name()]]));

        $this->assertEquals(null, $op->validate($parser, [IfElseOp::name(), [DummyOperation::name()], [DummyOperation::name()], [DummyOperation::name()]]));
    }

    public function testSubParamValidate()
    {
        $op = new IfElseOp();
        $parser = $this->getParser([DummyFailOperation::class, DummyOperation::class]);

        $this->assertEquals([
            'if_expression_invalid' => [DummyFailOperation::name(), ['failed' => true]]
        ], $op->validate($parser, [IfElseOp::name(), [DummyFailOperation::name()], [DummyOperation::name()], [DummyOperation::name()]]));

        $this->assertEquals([
            'true_expression_invalid' => [DummyFailOperation::name(), ['failed' => true]]
        ], $op->validate($parser, [IfElseOp::name(), [DummyOperation::name()], [DummyFailOperation::name()], [DummyOperation::name()]]));


        $this->assertEquals([
            'false_expression_invalid' => [DummyFailOperation::name(), ['failed' => true]]
        ], $op->validate($parser, [IfElseOp::name(), [DummyOperation::name()], [DummyOperation::name()], [DummyFailOperation::name()]]));
    }

    public function testEvaluateTruthy()
    {
        $op = new IfElseOp();
        $parser = $this->getParser([DummyReturnOperation::class]);

        $expression = [IfElseOp::name(), [DummyReturnOperation::name(), true], [DummyReturnOperation::name(), 'trueValue'], [DummyReturnOperation::name(), 'falseValue']];
        $this->assertEquals('trueValue', $op->evaluate($parser, $expression));
    }

    public function testEvaluateFalsy()
    {
        $op = new IfElseOp();
        $parser = $this->getParser([DummyReturnOperation::class]);

        $expression = [IfElseOp::name(), [DummyReturnOperation::name(), false], [DummyReturnOperation::name(), 'trueValue'], [DummyReturnOperation::name(), 'falseValue']];
        $this->assertEquals('falseValue', $op->evaluate($parser, $expression));
    }
}