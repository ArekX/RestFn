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

use ArekX\RestFn\Parser\Ops\RunOp;
use tests\Parser\_mock\DummyAction;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyOperation;
use tests\Parser\_mock\DummyReturnOperation;

class RunOpTest extends OpTestCase
{
    public ?string $opClass = RunOp::class;

    public function testValidateEmptyValue()
    {
        $error = [
            'min_parameters' => 3,
            'max_parameters' => 3
        ];

        $this->assertValidated($error);
        $this->assertValidated($error, DummyOperation::op());
        $this->assertValidated(null, DummyOperation::op(), DummyOperation::op());
    }

    public function testActionValue()
    {
        $this->assertValidated(null, DummyReturnOperation::op('testAction'), DummyOperation::op());
        $this->assertValidated(null, 'testAction', DummyOperation::op());

        $this->assertValidated([
            'invalid_action_value' => 1
        ], 1, DummyOperation::op());

        $this->assertValidated([
            'invalid_action_expression' => DummyFailOperation::error()
        ], DummyFailOperation::op(), DummyOperation::op());
    }

    public function testDataValue()
    {
        $this->assertValidated(null, 'testAction', DummyReturnOperation::op([]));
        $this->assertValidated(null, 'testAction', 1);
        $this->assertValidated(null, 'testAction', 1.5);
        $this->assertValidated(null, 'testAction', '1.5');
        $this->assertValidated(null, 'testAction', true);
        $this->assertValidated(null, 'testAction', false);
        $this->assertValidated(null, 'testAction', null);

        $this->assertValidated([
            'invalid_data_expression' => DummyFailOperation::error()
        ], DummyReturnOperation::op('testAction'), DummyFailOperation::op());
    }

    public function testEvaluate()
    {
        $parser = $this->createStandardParser();

        $parser->setContext('actions', [
            'testAction' => DummyAction::class
        ]);

        $data = ['test' => 'data', 'value' => rand(1, 5000)];

        $this->assertEvaluatedWithParser($parser, [
            'result' => 1
        ], 'testAction', DummyReturnOperation::op($data));
    }

    public function testEvaluateMissingAction()
    {
        $parser = $this->createStandardParser();

        $data = ['test' => 'data', 'value' => rand(1, 5000)];

        $this->expectException(\Exception::class);

        $this->assertEvaluatedWithParser($parser, [
            'result' => 1
        ], 'testAction', DummyReturnOperation::op($data));
    }
}
