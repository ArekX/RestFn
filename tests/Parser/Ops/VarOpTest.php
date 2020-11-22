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

use ArekX\RestFn\Parser\Ops\VarOp;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyOperation;
use tests\Parser\_mock\DummyReturnOperation;

class VarOpTest extends OpTestCase
{
    public $opClass = VarOp::class;

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
        $parser = $this->createStandardParser();

        $this->assertEvaluatedWithParser($parser, null, "name");
        $this->assertEvaluatedWithParser($parser, null, DummyReturnOperation::op("name"));
        $this->assertEvaluatedWithParser($parser, 1, "name", DummyReturnOperation::op(1));
        $this->assertEvaluatedWithParser($parser, 1, "name");
        $this->assertEvaluatedWithParser($parser, 42, DummyReturnOperation::op("name"), DummyReturnOperation::op(42));
        $this->assertEvaluatedWithParser($parser, 42, DummyReturnOperation::op("name"));
    }

    public function testEvaluateTraversal()
    {
        $parser = $this->createStandardParser();
        $input = [
            "name" => "john doe"
        ];

        $this->assertEvaluatedWithParser($parser, null, "profile.name");
        $this->assertEvaluatedWithParser($parser, $input, "profile", DummyReturnOperation::op($input));
        $this->assertEvaluatedWithParser($parser, "john doe", "profile.name");
        $this->assertEvaluatedWithParser($parser, "john doe", DummyReturnOperation::op("profile.name"));
    }
}