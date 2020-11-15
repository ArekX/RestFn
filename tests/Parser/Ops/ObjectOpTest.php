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
    public function testValidateParameters()
    {
        $op = new ObjectOp();
        $parser = $this->getParser([DummyOperation::class]);
        $error = [
            'min_parameters' => 2,
            'max_parameters' => 2
        ];

        $this->assertEquals($error, $op->validate($parser, [ObjectOp::name()]));
        $this->assertEquals(null, $op->validate($parser, [ObjectOp::name(), []]));
    }

    public function testValidateObject()
    {
        $op = new ObjectOp();
        $parser = $this->getParser([DummyOperation::class, DummyFailOperation::class]);
        $error = [
            'invalid_object_expression' => ['name' => DummyFailOperation::errorValue()]
        ];

        $this->assertEquals($error, $op->validate($parser, [ObjectOp::name(), [
            'name' => [DummyFailOperation::name()]
        ]]));

        $this->assertEquals(null, $op->validate($parser, [ObjectOp::name(), [
            'name' => [DummyOperation::name()]
        ]]));
    }

    public function testEvaluate()
    {
        $this->assertResult([
            'name' => 'test',
            'age' => 22
        ], [
            'name' => [DummyReturnOperation::name(), 'test'],
            'age' => [DummyReturnOperation::name(), 22]
        ]);

        $this->assertResult([], []);
    }

    protected function assertResult($expectedResult, $object)
    {
        $op = new ObjectOp();
        $parser = $this->getParser([DummyReturnOperation::class]);

        $this->assertEquals($expectedResult, $op->evaluate($parser, [ObjectOp::name(), $object]));
    }
}