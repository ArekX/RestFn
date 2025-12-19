<?php

/**
 * Copyright 2025 Aleksandar Panic
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
    public ?string $opClass = MapOp::class;

    public function testValidateParameters()
    {
        $parameterError = ['min_parameters' => 4, 'max_parameters' => 4];

        $this->assertValidated($parameterError);
        $this->assertValidated($parameterError, DummyOperation::op());
        $this->assertValidated($parameterError, DummyOperation::op(), DummyOperation::op());
        $this->assertValidated(null, DummyOperation::op(), DummyOperation::op(), DummyOperation::op());
    }

    public function testValidateFromValues()
    {
        $this->assertValidated(null, 'key', DummyOperation::op(), DummyOperation::op());

        $this->assertValidated([
            'invalid_from_value' => 23
        ], 23, DummyOperation::op(), DummyOperation::op());

        $this->assertValidated([
            'invalid_from_expression' => DummyFailOperation::error()
        ], DummyFailOperation::op(), DummyOperation::op(), DummyOperation::op());
    }

    public function testValidateToValues()
    {
        $this->assertValidated(null, DummyOperation::op(), 'key', DummyOperation::op());

        $this->assertValidated([
            'invalid_to_value' => 23
        ], DummyOperation::op(), 23, DummyOperation::op());

        $this->assertValidated([
            'invalid_to_expression' => DummyFailOperation::error()
        ], DummyOperation::op(), DummyFailOperation::op(), DummyOperation::op());
    }

    public function testEvaluate()
    {
        $this->assertEvaluated([
            'john' => 'doe',
            'mark' => 'twain'
        ], 'first', 'last', DummyReturnOperation::op([
            ['first' => 'john', 'last' => 'doe'],
            ['first' => 'mark', 'last' => 'twain'],
        ]));

        $this->assertEvaluated([
            'john' => 'doe',
            'mark' => 'twain'
        ], DummyReturnOperation::op('first'), 'last', DummyReturnOperation::op([
            ['first' => 'john', 'last' => 'doe'],
            ['first' => 'mark', 'last' => 'twain'],
        ]));

        $this->assertEvaluated([
            'john' => 'doe',
            'mark' => 'twain'
        ], DummyReturnOperation::op('first'), DummyReturnOperation::op('last'), DummyReturnOperation::op([
            ['first' => 'john', 'last' => 'doe'],
            ['first' => 'mark', 'last' => 'twain'],
        ]));
    }

    public function testEvaluateWalkingThroughAnArray()
    {
        $this->assertEvaluated([
            'john' => 'doe',
            'mark' => 'twain'
        ], 'first.name', 'last.name', DummyReturnOperation::op([
            ['first' => ['name' => 'john'], 'last' => ['name' => 'doe']],
            ['first' => ['name' => 'mark'], 'last' => ['name' => 'twain']],
        ]));
    }

    public function testEvaluateInvalidKey()
    {
        $this->expectException(\Exception::class);
        $this->assertEvaluated([
            'john' => 'doe',
            'mark' => 'twain'
        ], 'first.name', 'last.name', DummyReturnOperation::op([
            ['last' => ['name' => 'doe']],
            ['last' => ['name' => 'twain']],
        ]));
    }
}
