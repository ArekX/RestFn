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


use ArekX\RestFn\Parser\Contracts\OperationInterface;
use ArekX\RestFn\Parser\Parser;
use tests\Parser\_mock\DummyCalledOperation;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyOperation;
use tests\Parser\_mock\DummyReturnOperation;
use tests\TestCase;

class OpTestCase extends TestCase
{
    public ?string $opClass;

    public function assertValidated($expectedResult, ...$opParams)
    {
        $parser = $this->createStandardParser();

        /** @var OperationInterface $instance */
        $instance = new $this->opClass();

        DummyCalledOperation::$validated = false;

        $this->assertEquals($expectedResult, $instance->validate($parser, [$this->opClass::name(), ...$opParams]));
    }

    public function getParser($ops = [])
    {
        $parser = new Parser();
        $parser->injector = $this->getInjector();

        $parser->configure(['ops' => $ops]);
        return $parser;
    }

    public function assertEvaluatedWithParser($parser, $expectedResult, ...$opParams)
    {
        $op = $this->opClass;

        /** @var OperationInterface $instance */
        $instance = $this->getInjector()->make($op);

        DummyCalledOperation::$evaluated = false;

        $this->assertEquals($expectedResult, $instance->evaluate($parser, [$op::name(), ...$opParams]));
    }

    public function assertEvaluated($expectedResult, ...$opParams)
    {
        $this->assertEvaluatedWithParser($this->createStandardParser(), $expectedResult, ...$opParams);
    }

    protected function createStandardParser(): Parser
    {
        return $this->getParser([
            DummyOperation::class,
            DummyFailOperation::class,
            DummyReturnOperation::class,
            DummyCalledOperation::class
        ]);
    }
}
