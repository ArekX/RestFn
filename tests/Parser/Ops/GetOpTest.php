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

use ArekX\RestFn\Parser\Ops\GetOp;
use tests\Parser\_mock\DummyCalledOperation;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyOperation;
use tests\Parser\_mock\DummyReturnOperation;

class GetOpTest extends OpTestCase
{
    public ?string $opClass = GetOp::class;

    public function testValidateEmptyValue()
    {
        $error = ['min_parameters' => 3];

        $this->assertValidated($error);
        $this->assertValidated($error, 'test');
    }

    public function testValidateParamGetter()
    {
        $this->assertValidated(['invalid_getter_value' => 2], 2, null);
    }

    public function testValidateParamGetterCanBeStringOrExpression()
    {
        $this->assertValidated(null, 'string', DummyOperation::op());
        $this->assertValidated(null, DummyOperation::op(), DummyOperation::op());
        $this->assertValidated(['invalid_getter_expression' => DummyFailOperation::error()], DummyFailOperation::op(), DummyOperation::op());
    }

    public function testValidateFromExpression()
    {
        $this->assertValidated([
            'invalid_value_expression' => DummyFailOperation::error()
        ], 'value', DummyFailOperation::op());

        $this->assertValidated([
            'invalid_value_expression' => DummyFailOperation::error()
        ], DummyReturnOperation::op('value'), DummyFailOperation::op());
    }

    public function testValidateDefaultExpression()
    {
        $this->assertValidated([
            'invalid_default_expression' => DummyFailOperation::error()
        ], 'value', DummyOperation::op(), DummyFailOperation::op());

        $this->assertValidated([
            'invalid_default_expression' => DummyFailOperation::error()
        ], DummyReturnOperation::op('value'), DummyOperation::op(), DummyFailOperation::op());
    }

    public function testEvaluateDirectName()
    {
        $this->assertEvaluated('value1', 'path.to.item', DummyReturnOperation::op([
            'path.to.item' => 'value1',
            'path' => [
                'to' => [
                    'item' => 'value2'
                ]
            ]
        ]));
    }

    public function testEvaluateExpressionName()
    {
        $this->assertEvaluated('value1', DummyReturnOperation::op('path.to.item'), DummyReturnOperation::op([
            'path.to.item' => 'value1',
            'path' => [
                'to' => [
                    'item' => 'value2'
                ]
            ]
        ]));
    }

    public function testWalkThroughArray()
    {
        $this->assertEvaluated('value', DummyReturnOperation::op('path.to.item'), DummyReturnOperation::op([
            'path' => [
                'to' => [
                    'item' => 'value'
                ]
            ]
        ]));
    }

    public function testDefaultValue()
    {
        $this->assertEvaluated('default', 'path.to.item.value', DummyReturnOperation::op([
            'path' => [
                'to' => 'item.value'
            ]
        ]), DummyReturnOperation::op('default'));
    }

    public function testDefaultValueNotEvaluatedUntilRequired()
    {
        $this->assertEvaluated('default', 'non.existing.path', DummyReturnOperation::op([
            'path' => [
                'to' => 'value'
            ]
        ]), DummyCalledOperation::op('default'));

        $this->assertTrue(DummyCalledOperation::$evaluated);

        $this->assertEvaluated('value', 'path.to', DummyReturnOperation::op([
            'path' => [
                'to' => 'value'
            ]
        ]), DummyCalledOperation::op('default'));

        $this->assertFalse(DummyCalledOperation::$evaluated);
    }
}
