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

use ArekX\RestFn\Parser\Ops\ValueOp;

class ValueOpTest extends OpTestCase
{
    public ?string $opClass = ValueOp::class;

    public function testValidateEmptyValue()
    {
        $this->assertValidated(['min_parameters' => 2, 'max_parameters' => 2]);
    }

    public function testValidatePassing()
    {
        $this->assertValidated(null, 1);
    }

    public function testAlwaysReturnsSetValue()
    {
        $rand = rand(1, 2000);
        $this->assertEvaluated($rand, $rand);
        $this->assertEvaluated(10, 10);
        $this->assertEvaluated('a', 'a');
        $this->assertEvaluated(null, null);
        $this->assertEvaluated(true, true);
        $this->assertEvaluated(false, false);
    }
}
