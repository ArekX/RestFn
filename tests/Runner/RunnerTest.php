<?php

/**
 * Copyright 2026 Aleksandar Panic
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

namespace tests\Runner;

use ArekX\RestFn\DI\Container;
use ArekX\RestFn\App\WebApp;
use ArekX\RestFn\Runner\Contracts\ResponseInterface;
use ArekX\RestFn\Runner\Exceptions\InvalidRequestException;
use ArekX\RestFn\Runner\Request;
use ArekX\RestFn\Runner\Runner;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyReturnOperation;
use tests\Runner\_mock\InnerMiddleware;
use tests\Runner\_mock\OuterMiddleware;
use tests\Runner\_mock\PassthroughResponse;
use tests\Runner\_mock\Recorder;
use tests\Runner\_mock\ShortCircuitMiddleware;
use tests\Runner\_mock\StubRequestParser;
use tests\Runner\_mock\WrapResultMiddleware;
use tests\TestCase;

class RunnerTest extends TestCase
{
    protected function makeRunner(Request $request, array $middleware = []): Runner
    {
        $container = new Container([
            // Use a passthrough response so assertions see the raw result, not JSON.
            'aliases' => [ResponseInterface::class => PassthroughResponse::class] + WebApp::DEFAULT_ALIASES,
            'config' => [
                'global' => [
                    'ops' => [
                        DummyReturnOperation::name() => DummyReturnOperation::class,
                        DummyFailOperation::name() => DummyFailOperation::class,
                    ],
                    'runner' => ['middleware' => $middleware],
                ],
            ],
        ]);

        return $container->make(Runner::class, ['requestParser' => new StubRequestParser($request)]);
    }

    public function testRunValidatesAndEvaluatesTheRequestBody()
    {
        $runner = $this->makeRunner(new Request(DummyReturnOperation::op(42)));

        $this->assertSame(42, $runner->run());
    }

    public function testRunThrowsWhenValidationFails()
    {
        $runner = $this->makeRunner(new Request(DummyFailOperation::op()));

        $this->expectException(InvalidRequestException::class);

        $runner->run();
    }

    public function testValidationErrorsAreCarriedOnTheException()
    {
        $runner = $this->makeRunner(new Request(DummyFailOperation::op()));

        try {
            $runner->run();
            $this->fail('Expected InvalidRequestException.');
        } catch (InvalidRequestException $exception) {
            $this->assertSame([DummyFailOperation::name(), ['failed' => true]], $exception->errors);
        }
    }

    public function testMiddlewareRunsAroundHandlingInOrder()
    {
        Recorder::$log = [];

        $runner = $this->makeRunner(
            new Request(DummyReturnOperation::op(1)),
            [OuterMiddleware::class, InnerMiddleware::class]
        );

        $result = $runner->run();

        $this->assertSame(1, $result);
        $this->assertSame(
            ['outer:before', 'inner:before', 'inner:after', 'outer:after'],
            Recorder::$log
        );
    }

    public function testMiddlewareCanTransformTheResult()
    {
        $runner = $this->makeRunner(
            new Request(DummyReturnOperation::op('value')),
            [WrapResultMiddleware::class]
        );

        $this->assertSame(['result' => 'value'], $runner->run());
    }

    public function testMiddlewareCanShortCircuitWithoutEvaluating()
    {
        $runner = $this->makeRunner(
            new Request(DummyFailOperation::op()), // would fail validation if reached
            [ShortCircuitMiddleware::class]
        );

        $this->assertSame('short-circuited', $runner->run());
    }
}
