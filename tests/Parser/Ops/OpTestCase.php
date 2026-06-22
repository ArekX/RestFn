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


use ArekX\RestFn\DI\Container;
use ArekX\RestFn\Parser\Context;
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

    /**
     * Builds the operation under test through a container so its evaluator and
     * any Config-driven values are injected exactly as they are at runtime. The
     * evaluator is a parser registered with the standard dummy operations so
     * recursion into sub-expressions works.
     *
     * @param array $opConfig Per-class config for the operation under test.
     * @return OperationInterface
     */
    protected function makeOp(array $opConfig = []): OperationInterface
    {
        $container = new Container([
            'config' => [
                'overrides' => [
                    Parser::class => ['ops' => $this->standardOps()],
                    $this->opClass => $opConfig,
                ],
            ],
        ]);

        $container->make(Parser::class);

        return $container->make($this->opClass);
    }

    protected function standardOps(): array
    {
        return [
            DummyOperation::class,
            DummyFailOperation::class,
            DummyReturnOperation::class,
            DummyCalledOperation::class,
        ];
    }

    public function assertValidated($expectedResult, ...$opParams)
    {
        $this->assertValidatedWithConfig([], $expectedResult, ...$opParams);
    }

    public function assertValidatedWithConfig(array $opConfig, $expectedResult, ...$opParams)
    {
        DummyCalledOperation::$validated = false;

        $instance = $this->makeOp($opConfig);

        $this->assertEquals($expectedResult, $instance->validate([$this->opClass::name(), ...$opParams], new Context()));
    }

    public function assertEvaluated($expectedResult, ...$opParams)
    {
        $this->assertEvaluatedWithConfig([], $expectedResult, ...$opParams);
    }

    public function assertEvaluatedWithConfig(array $opConfig, $expectedResult, ...$opParams)
    {
        DummyCalledOperation::$evaluated = false;

        $instance = $this->makeOp($opConfig);

        $this->assertEquals($expectedResult, $instance->evaluate([$this->opClass::name(), ...$opParams], new Context()));
    }
}
