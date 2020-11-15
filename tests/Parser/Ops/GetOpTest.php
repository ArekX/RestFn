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

use ArekX\RestFn\Parser\Ops\GetOp;
use tests\Parser\_mock\DummyCalledOperation;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyOperation;
use tests\Parser\_mock\DummyReturnOperation;

class GetOpTest extends OpTestCase
{
    public function testValidateEmptyValue()
    {
        $op = new GetOp();
        $this->assertEquals(['min_parameters' => 2], $op->validate($this->getParser(), []));
        $this->assertEquals(['min_parameters' => 2], $op->validate($this->getParser(), [GetOp::name()]));
        $this->assertEquals(['min_parameters' => 2], $op->validate($this->getParser(), [GetOp::name(), 'test']));
    }

    public function testValidateParamGetter()
    {
        $op = new GetOp();
        $parser = $this->getParser([]);
        $this->assertEquals(['invalid_getter_value' => 2], $op->validate($parser, [
            GetOp::name(),
            2,
            null
        ]));
    }

    public function testValidateParamGetterCanBeStringOrExpression()
    {
        $op = new GetOp();
        $parser = $this->getParser([
            DummyOperation::class,
            DummyFailOperation::class
        ]);
        $this->assertEquals(null, $op->validate($parser, [
            GetOp::name(),
            'string',
            [DummyOperation::name()]
        ]));

        $this->assertEquals(null, $op->validate($parser, [
            GetOp::name(),
            [DummyOperation::name()],
            [DummyOperation::name()]
        ]));

        $this->assertEquals([
            'invalid_getter_expression' => [DummyFailOperation::name(), ['failed' => true]]
        ], $op->validate($parser, [
            GetOp::name(),
            [DummyFailOperation::name()],
            [DummyOperation::name()]
        ]));
    }

    public function testValidateFromExpression()
    {
        $op = new GetOp();
        $parser = $this->getParser([
            DummyOperation::class,
            DummyFailOperation::class,
            DummyReturnOperation::class
        ]);

        $this->assertEquals([
            'invalid_value_expression' => [DummyFailOperation::name(), ['failed' => true]]
        ], $op->validate($parser, [
            GetOp::name(),
            'value',
            [DummyFailOperation::name()]
        ]));

        $this->assertEquals([
            'invalid_value_expression' => [DummyFailOperation::name(), ['failed' => true]]
        ], $op->validate($parser, [
            GetOp::name(),
            [DummyReturnOperation::name(), 'value'],
            [DummyFailOperation::name()]
        ]));
    }

    public function testValidateDefaultExpression()
    {
        $op = new GetOp();
        $parser = $this->getParser([
            DummyOperation::class,
            DummyFailOperation::class,
            DummyReturnOperation::class
        ]);

        $this->assertEquals([
            'invalid_default_expression' => [DummyFailOperation::name(), ['failed' => true]]
        ], $op->validate($parser, [
            GetOp::name(),
            'value',
            [DummyOperation::name()],
            [DummyFailOperation::name()]
        ]));

        $this->assertEquals([
            'invalid_default_expression' => [DummyFailOperation::name(), ['failed' => true]]
        ], $op->validate($parser, [
            GetOp::name(),
            [DummyReturnOperation::name(), 'value'],
            [DummyOperation::name()],
            [DummyFailOperation::name()]
        ]));
    }

    public function testEvaluateDirectName()
    {
        $this->assertEvaluatedResult('value1', 'path.to.item', [
            'path.to.item' => 'value1',
            'path' => [
                'to' => [
                    'item' => 'value2'
                ]
            ]
        ]);
    }

    protected function assertEvaluatedResult($expectedResult, $path, $data, $default = null)
    {
        $op = new GetOp();
        $parser = $this->getParser([
            DummyReturnOperation::class,
            DummyCalledOperation::class
        ]);

        DummyCalledOperation::$evaluated = false;

        $expression = [GetOp::name(), $path, [DummyReturnOperation::name(), $data]];

        if ($default) {
            $expression[] = $default;
        }

        $result = $op->evaluate($parser, $expression);
        $this->assertEquals($expectedResult, $result);
    }

    public function testEvaluateExpressionName()
    {
        $this->assertEvaluatedResult('value1', [DummyReturnOperation::name(), 'path.to.item'], [
            'path.to.item' => 'value1',
            'path' => [
                'to' => [
                    'item' => 'value2'
                ]
            ]
        ]);
    }

    public function testWalkThroughArray()
    {
        $this->assertEvaluatedResult('value', [DummyReturnOperation::name(), 'path.to.item'], [
            'path' => [
                'to' => [
                    'item' => 'value'
                ]
            ]
        ]);
    }

    public function testDefaultValue()
    {
        $this->assertEvaluatedResult('default', 'path.to.item.value', [
            'path' => [
                'to' => 'item.value'
            ]
        ], [DummyReturnOperation::name(), 'default']);
    }

    public function testDefaultValueNotEvaluatedUntilRequired()
    {
        $this->assertEvaluatedResult('default', 'non.existing.path', [
            'path' => [
                'to' => 'value'
            ]
        ], [DummyCalledOperation::name(), 'default']);

        $this->assertTrue(DummyCalledOperation::$evaluated);

        $this->assertEvaluatedResult('value', 'path.to', [
            'path' => [
                'to' => 'value'
            ]
        ], [DummyCalledOperation::name(), 'default']);

        $this->assertFalse(DummyCalledOperation::$evaluated);
    }
}