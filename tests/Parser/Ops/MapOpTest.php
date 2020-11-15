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

use ArekX\RestFn\Parser\Ops\MapOp;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyOperation;
use tests\Parser\_mock\DummyReturnOperation;

class MapOpTest extends OpTestCase
{
    public function testValidateParameters()
    {
        $op = new MapOp();
        $parser = $this->getParser([DummyOperation::class]);
        $this->assertEquals([
            'min_parameters' => 4,
            'max_parameters' => 4
        ], $op->validate($parser, []));

        $this->assertEquals([
            'min_parameters' => 4,
            'max_parameters' => 4
        ], $op->validate($parser, [MapOp::name()]));

        $this->assertEquals([
            'min_parameters' => 4,
            'max_parameters' => 4
        ], $op->validate($parser, [MapOp::name(), [DummyOperation::name()]]));

        $this->assertEquals([
            'min_parameters' => 4,
            'max_parameters' => 4
        ], $op->validate($parser, [MapOp::name(), [DummyOperation::name()], [DummyOperation::name()]]));

        $this->assertEquals(null, $op->validate($parser, [MapOp::name(), [DummyOperation::name()], [DummyOperation::name()], [DummyOperation::name()]]));
    }

    public function testValidateFromValues()
    {
        $op = new MapOp();
        $parser = $this->getParser([DummyOperation::class, DummyFailOperation::class]);

        $this->assertEquals(null, $op->validate($parser, [MapOp::name(), 'key', [DummyOperation::name()], [DummyOperation::name()]]));
        $this->assertEquals([
            'invalid_from_value' => 23
        ], $op->validate($parser, [MapOp::name(), 23, [DummyOperation::name()], [DummyOperation::name()]]));

        $this->assertEquals([
            'invalid_from_expression' => [DummyFailOperation::name(), ['failed' => true]]
        ], $op->validate($parser, [MapOp::name(), [DummyFailOperation::name()], [DummyOperation::name()], [DummyOperation::name()]]));
    }

    public function testValidateToValues()
    {
        $op = new MapOp();
        $parser = $this->getParser([DummyOperation::class, DummyFailOperation::class]);

        $this->assertEquals(null, $op->validate($parser, [MapOp::name(), [DummyOperation::name()], 'key', [DummyOperation::name()]]));
        $this->assertEquals([
            'invalid_to_value' => 23
        ], $op->validate($parser, [MapOp::name(), [DummyOperation::name()], 23, [DummyOperation::name()]]));

        $this->assertEquals([
            'invalid_to_expression' => [DummyFailOperation::name(), ['failed' => true]]
        ], $op->validate($parser, [MapOp::name(), [DummyOperation::name()], [DummyFailOperation::name()], [DummyOperation::name()]]));
    }

    public function testEvaluate()
    {
        $this->assertResult([
            'john' => 'doe',
            'mark' => 'twain'
        ], 'first', 'last', [
            DummyReturnOperation::name(),
            [
                ['first' => 'john', 'last' => 'doe'],
                ['first' => 'mark', 'last' => 'twain'],
            ]
        ]);

        $this->assertResult([
            'john' => 'doe',
            'mark' => 'twain'
        ], [DummyReturnOperation::name(), 'first'], 'last', [
            DummyReturnOperation::name(),
            [
                ['first' => 'john', 'last' => 'doe'],
                ['first' => 'mark', 'last' => 'twain'],
            ]
        ]);

        $this->assertResult([
            'john' => 'doe',
            'mark' => 'twain'
        ], [DummyReturnOperation::name(), 'first'], [DummyReturnOperation::name(), 'last'], [
            DummyReturnOperation::name(),
            [
                ['first' => 'john', 'last' => 'doe'],
                ['first' => 'mark', 'last' => 'twain'],
            ]
        ]);
    }

    protected function assertResult($expectedResult, $from, $to, $expression)
    {
        $op = new MapOp();
        $parser = $this->getParser([DummyReturnOperation::class]);

        $this->assertEquals($expectedResult, $op->evaluate($parser, [MapOp::name(), $from, $to, $expression]));
    }

    public function testEvaluateWalkingThroughAnArray()
    {
        $this->assertResult([
            'john' => 'doe',
            'mark' => 'twain'
        ], 'first.name', 'last.name', [
            DummyReturnOperation::name(),
            [
                ['first' => ['name' => 'john'], 'last' => ['name' => 'doe']],
                ['first' => ['name' => 'mark'], 'last' => ['name' => 'twain']],
            ]
        ]);
    }

    public function testEvaluateInvalidKey()
    {
        $this->expectException(\Exception::class);
        $this->assertResult([
            'john' => 'doe',
            'mark' => 'twain'
        ], 'first.name', 'last.name', [
            DummyReturnOperation::name(),
            [
                ['last' => ['name' => 'doe']],
                ['last' => ['name' => 'twain']],
            ]
        ]);
    }
}