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

use ArekX\RestFn\Parser\Context;
use ArekX\RestFn\Runner\Exceptions\InvalidRequestException;
use ArekX\RestFn\Runner\Middleware\ErrorMiddleware;
use ArekX\RestFn\Runner\Request;
use tests\TestCase;

class ErrorMiddlewareTest extends TestCase
{
    private function process(ErrorMiddleware $middleware, callable $next): mixed
    {
        return $middleware->process(new Request([]), new Context(), $next);
    }

    public function testPassesResultThroughWhenNothingThrows()
    {
        $result = $this->process(new ErrorMiddleware(), static fn(): string => 'ok');

        $this->assertSame('ok', $result);
    }

    public function testHidesInternalErrorsInProduction()
    {
        $result = $this->process(
            new ErrorMiddleware(debug: false),
            static fn() => throw new \RuntimeException('database password is hunter2')
        );

        $this->assertSame(['error' => 'An unexpected error occurred.'], $result);
    }

    public function testRevealsInternalErrorsWithDebugEnabled()
    {
        $result = $this->process(
            new ErrorMiddleware(debug: true),
            static fn() => throw new \RuntimeException('boom')
        );

        $this->assertSame('boom', $result['error']);
        $this->assertSame(\RuntimeException::class, $result['debug']['type']);
        $this->assertIsArray($result['debug']['trace']);
    }

    public function testClientErrorMessageAndDetailsAreExposedInProduction()
    {
        $result = $this->process(
            new ErrorMiddleware(debug: false),
            static fn() => throw new InvalidRequestException(
                'Request validation failed.',
                ['get', ['failed' => true]]
            )
        );

        $this->assertSame('Request validation failed.', $result['error']);
        $this->assertSame(['get', ['failed' => true]], $result['details']);
        $this->assertArrayNotHasKey('debug', $result);
    }
}
