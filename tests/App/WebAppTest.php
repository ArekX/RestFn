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

namespace tests\App;

use ArekX\RestFn\App\Contracts\ApplicationInterface;
use ArekX\RestFn\App\WebApp;
use ArekX\RestFn\Parser\Parser;
use ArekX\RestFn\Runner\Contracts\RequestParserInterface;
use tests\App\_mock\FixedRequestParser;
use tests\App\_mock\StubApp;
use tests\Parser\_mock\DummyReturnOperation;
use tests\TestCase;

class WebAppTest extends TestCase
{
    private function inputStreamFor(string $body): string
    {
        return 'data://text/plain;base64,' . base64_encode($body);
    }

    public function testCreateDefaultRunsTheRequestUsingDefaultWiring()
    {
        // Configuration normally goes under 'global'.
        $result = WebApp::createDefault([
            'config' => [
                'global' => [
                    'runner' => ['inputStream' => $this->inputStreamFor('["return", 42]')],
                    'ops' => [DummyReturnOperation::name() => DummyReturnOperation::class],
                ],
            ],
        ])->run();

        // The default response serializes the result as JSON.
        $this->assertSame(json_encode(42), $result);
    }

    public function testPassedAliasesOverrideTheDefaults()
    {
        // Override the default request parser; FixedRequestParser always returns ["return", 99].
        $result = WebApp::createDefault([
            'aliases' => [
                RequestParserInterface::class => FixedRequestParser::class,
            ],
            'config' => [
                'global' => ['ops' => [DummyReturnOperation::name() => DummyReturnOperation::class]],
            ],
        ])->run();

        $this->assertSame(json_encode(99), $result);
    }

    public function testCreateDefaultReturnsTheApplicationWithoutRunning()
    {
        $app = WebApp::createDefault();

        $this->assertInstanceOf(ApplicationInterface::class, $app);
    }

    public function testBuiltInOperationsAreUsableByDefault()
    {
        // No 'ops' configured: the built-in operations are available by default.
        $result = WebApp::createDefault([
            'config' => [
                'global' => [
                    'runner' => ['inputStream' => $this->inputStreamFor('["value", 42]')],
                ],
            ],
        ])->run();

        $this->assertSame(json_encode(42), $result);
    }

    public function testApplicationItselfIsOverridable()
    {
        // Aliasing ApplicationInterface swaps the whole application.
        $result = WebApp::createDefault([
            'aliases' => [
                ApplicationInterface::class => StubApp::class,
            ],
        ])->run();

        $this->assertSame('custom-app-result', $result);
    }

    public function testDefaultOpsAreKeyedByOperationName()
    {
        // The default operation map must key each operation by its own name() so
        // requests dispatch correctly.
        foreach (WebApp::DEFAULT_OPS as $name => $class) {
            $this->assertSame($name, $class::name());
        }
    }

    public function testErrorsAreRenderedAsJsonByTheDefaultErrorMiddleware()
    {
        // An unknown operation surfaces as a client error rendered as JSON, not an
        // uncaught exception. Debug is off by default, so no debug block leaks.
        $result = WebApp::createDefault([
            'config' => [
                'global' => [
                    'runner' => ['inputStream' => $this->inputStreamFor('["nope"]')],
                    'ops' => [DummyReturnOperation::name() => DummyReturnOperation::class],
                ],
            ],
        ])->run();

        $decoded = json_decode($result, true);

        $this->assertSame('Invalid operation: nope', $decoded['error']);
        $this->assertArrayNotHasKey('debug', $decoded);
    }

    public function testMalformedJsonBodyIsRenderedAsJson()
    {
        // A body that is not valid JSON fails in the request parser, before the
        // pipeline. It must still come back as a JSON error, not an uncaught
        // exception.
        $result = WebApp::createDefault([
            'config' => [
                'global' => [
                    'runner' => ['inputStream' => $this->inputStreamFor('{not valid json')],
                ],
            ],
        ])->run();

        $decoded = json_decode($result, true);

        $this->assertStringContainsString('not valid JSON', $decoded['error']);
    }
}
