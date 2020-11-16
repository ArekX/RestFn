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


use ArekX\RestFn\Parser\Contracts\Operation;
use ArekX\RestFn\Parser\Parser;
use tests\Parser\_mock\DummyCalledOperation;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyOperation;
use tests\Parser\_mock\DummyReturnOperation;
use tests\TestCase;

class OpTestCase extends TestCase
{
    public $opClass;

    public function assertValidated($expectedResult, ...$opParams)
    {
        $parser = $this->createStandardParser();

        /** @var Operation $op */
        $op = $this->opClass;

        /** @var Operation $instance */
        $instance = new $op();

        DummyCalledOperation::$validated = false;

        $this->assertEquals($expectedResult, $instance->validate($parser, [$op::name(), ...$opParams]));
    }

    public function getParser($ops = [])
    {
        $parser = new Parser();
        $parser->configure(['ops' => $ops]);
        return $parser;
    }

    public function assertEvaluated($expectedResult, ...$opParams)
    {
        $parser = $this->createStandardParser();

        /** @var Operation $op */
        $op = $this->opClass;

        /** @var Operation $instance */
        $instance = new $op();

        DummyCalledOperation::$evaluated = false;

        $this->assertEquals($expectedResult, $instance->evaluate($parser, [$op::name(), ...$opParams]));
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