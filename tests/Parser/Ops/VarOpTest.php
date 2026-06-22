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

use ArekX\RestFn\Parser\Context;
use ArekX\RestFn\Parser\Ops\SequenceOp;
use ArekX\RestFn\Parser\Ops\VarOp;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyOperation;
use tests\Parser\_mock\DummyReturnOperation;

class VarOpTest extends OpTestCase
{
    public ?string $opClass = VarOp::class;

    public function testValidateEmptyValue()
    {
        $error = [
            'min_parameters' => 2,
            'max_parameters' => 3
        ];
        $this->assertValidated($error);

        $this->assertValidated(null, DummyOperation::op());
        $this->assertValidated(null, DummyOperation::op(), DummyOperation::op());
        $this->assertValidated($error, DummyOperation::op(), DummyOperation::op(), DummyOperation::op());
    }

    public function testValidateName()
    {
        $this->assertValidated(null, "name");
        $this->assertValidated(null, DummyOperation::op());
        $this->assertValidated(["invalid_name_value" => 1], 1);
        $this->assertValidated(["invalid_name_value" => 1.5], 1.5);
        $this->assertValidated(["invalid_name_value" => true], true);
        $this->assertValidated(["invalid_name_value" => false], false);
        $this->assertValidated(["invalid_name_value" => null], null);
        $this->assertValidated(["invalid_name_expression" => DummyFailOperation::error()], DummyFailOperation::op());
    }

    public function testValidateFrom()
    {
        $this->assertValidated(null, DummyOperation::op(), DummyOperation::op());
        $this->assertValidated(["invalid_value_expression" => DummyFailOperation::error()], DummyOperation::op(), DummyFailOperation::op());
    }

    public function testEvaluateNonExisting()
    {
        $this->assertEvaluated(null, "name");
    }

    public function testEvaluateSet()
    {
        $parser = $this->makeParser([
            VarOp::class,
            SequenceOp::class,
            DummyReturnOperation::class,
        ]);

        // An unset variable evaluates to null.
        $this->assertSame(null, $parser->evaluate(['var', 'name'], new Context()));

        // Setting a variable returns the assigned value.
        $this->assertSame(1, $parser->evaluate(['var', 'name', DummyReturnOperation::op(1)], new Context()));

        // The binding is readable later within the same evaluation.
        $this->assertSame(1, $parser->evaluate([
            'sequence',
            ['var', 'name', DummyReturnOperation::op(1)],
            ['var', 'name'],
        ], new Context()));

        // The name itself may be an expression for both the set and the get.
        $this->assertSame(42, $parser->evaluate([
            'sequence',
            ['var', DummyReturnOperation::op('name'), DummyReturnOperation::op(42)],
            ['var', DummyReturnOperation::op('name')],
        ], new Context()));
    }

    public function testVariablesLiveOnTheContextNotTheParser()
    {
        $parser = $this->makeParser([
            VarOp::class,
            DummyReturnOperation::class,
        ]);

        $context = new Context();
        $parser->evaluate(['var', 'name', DummyReturnOperation::op('value')], $context);

        // A fresh context starts clean - no state leaks through the stateless parser.
        $this->assertSame(null, $parser->evaluate(['var', 'name'], new Context()));

        // Reusing the same context preserves bindings - the caller owns the lifecycle.
        $this->assertSame('value', $parser->evaluate(['var', 'name'], $context));
    }

    public function testEvaluateTraversal()
    {
        $parser = $this->makeParser([
            VarOp::class,
            SequenceOp::class,
            DummyReturnOperation::class,
        ]);
        $input = [
            "name" => "john doe"
        ];

        // An unset path evaluates to null.
        $this->assertSame(null, $parser->evaluate(['var', 'profile.name'], new Context()));

        // A dotted path traverses a stored array within the same evaluation.
        $this->assertSame('john doe', $parser->evaluate([
            'sequence',
            ['var', 'profile', DummyReturnOperation::op($input)],
            ['var', 'profile.name'],
        ], new Context()));

        // The get path may itself be an expression.
        $this->assertSame('john doe', $parser->evaluate([
            'sequence',
            ['var', 'profile', DummyReturnOperation::op($input)],
            ['var', DummyReturnOperation::op('profile.name')],
        ], new Context()));
    }
}
