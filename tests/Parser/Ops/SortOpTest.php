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

use ArekX\RestFn\Parser\Ops\SortOp;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyOperation;
use tests\Parser\_mock\DummyReturnOperation;

class SortOpTest extends OpTestCase
{
    public ?string $opClass = SortOp::class;

    public function testValidateEmptyValue()
    {
        $error = [
            'min_parameters' => 3,
            'max_parameters' => 4
        ];

        $this->assertValidated($error);

        $this->assertValidated($error, DummyOperation::op());
        $this->assertValidated(null, DummyOperation::op(), DummyOperation::op());
        $this->assertValidated(null, DummyOperation::op(), DummyOperation::op(), DummyOperation::op());
    }

    public function testValidateBySignatureBy()
    {
        $this->assertValidated(['invalid_by_value' => 1.5], 1.5, DummyOperation::op(), DummyOperation::op());

        $this->assertValidated([
            'invalid_by_expression' => DummyFailOperation::error(),
        ], DummyFailOperation::op(), DummyOperation::op(), DummyOperation::op());

        $this->assertValidated(null, 1, DummyOperation::op(), DummyOperation::op());
        $this->assertValidated(null, 'property', DummyOperation::op(), DummyOperation::op());
    }

    public function testValidateBySignatureDirection()
    {
        $this->assertValidated(['invalid_direction_value' => 1], DummyOperation::op(), 1, DummyOperation::op());

        $this->assertValidated([
            'invalid_direction_expression' => DummyFailOperation::error(),
        ], DummyOperation::op(), DummyFailOperation::op(), DummyOperation::op());

        $this->assertValidated(null, DummyOperation::op(), 'asc', DummyOperation::op());
        $this->assertValidated(null, DummyOperation::op(), 'desc', DummyOperation::op());
    }

    public function testValidateBySignatureFrom()
    {
        $this->assertValidated([
            'invalid_from_expression' => DummyFailOperation::error(),
        ], DummyOperation::op(), DummyOperation::op(), DummyFailOperation::op());
    }


    public function testValidateNormalSignatureDirection()
    {
        $this->assertValidated(['invalid_direction_value' => 1], 1, DummyOperation::op());

        $this->assertValidated([
            'invalid_direction_expression' => DummyFailOperation::error(),
        ], DummyFailOperation::op(), DummyOperation::op());

        $this->assertValidated(null, 'asc', DummyOperation::op());
        $this->assertValidated(null, 'desc', DummyOperation::op());
    }

    public function testValidateNormalSignatureFrom()
    {
        $this->assertValidated([
            'invalid_from_expression' => DummyFailOperation::error(),
        ], 'asc', DummyFailOperation::op());
    }

    public function testEvaluateBySignature()
    {
        $expectedAscending = fn () => [
            ['name' => 'mark', 'age' => 9],
            ['name' => 'john', 'age' => 10],
            ['name' => 'jeanne', 'age' => 55],
        ];

        $expectedDescending = fn () => [
            ['name' => 'jeanne', 'age' => 55],
            ['name' => 'john', 'age' => 10],
            ['name' => 'mark', 'age' => 9],
        ];

        $input = fn () => [
            ['name' => 'john', 'age' => 10],
            ['name' => 'mark', 'age' => 9],
            ['name' => 'jeanne', 'age' => 55],
        ];

        $this->assertEvaluated($expectedAscending(), 'age', 'asc', DummyReturnOperation::op($input()));
        $this->assertEvaluated($expectedAscending(), DummyReturnOperation::op('age'), 'asc', DummyReturnOperation::op($input()));
        $this->assertEvaluated($expectedAscending(), DummyReturnOperation::op('age'), DummyReturnOperation::op('asc'), DummyReturnOperation::op($input()));


        $this->assertEvaluated($expectedDescending(), 'age', 'desc', DummyReturnOperation::op($input()));
        $this->assertEvaluated($expectedDescending(), DummyReturnOperation::op('age'), 'desc', DummyReturnOperation::op($input()));
        $this->assertEvaluated($expectedDescending(), DummyReturnOperation::op('age'), DummyReturnOperation::op('desc'), DummyReturnOperation::op($input()));
    }

    public function testEvaluateNormalSignature()
    {
        $expectedAscending = fn () => [4, 5, 12, 22];

        $expectedDescending = fn () => [22, 12, 5, 4];

        $input = fn () => [5, 22, 4, 12];

        $this->assertEvaluated($expectedAscending(), 'asc', DummyReturnOperation::op($input()));
        $this->assertEvaluated($expectedAscending(), 'asc', DummyReturnOperation::op($input()));
        $this->assertEvaluated($expectedAscending(), DummyReturnOperation::op('asc'), DummyReturnOperation::op($input()));


        $this->assertEvaluated($expectedDescending(), 'desc', DummyReturnOperation::op($input()));
        $this->assertEvaluated($expectedDescending(), 'desc', DummyReturnOperation::op($input()));
        $this->assertEvaluated($expectedDescending(), DummyReturnOperation::op('desc'), DummyReturnOperation::op($input()));
    }
}
