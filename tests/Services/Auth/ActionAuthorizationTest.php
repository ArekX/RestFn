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

namespace tests\Services\Auth;

use ArekX\RestFn\DI\Container;
use ArekX\RestFn\App\WebApp;
use ArekX\RestFn\Parser\Context;
use ArekX\RestFn\Parser\Ops\RunOp;
use ArekX\RestFn\Parser\Parser;
use ArekX\RestFn\Services\Auth\Exceptions\AuthenticationRequiredException;
use ArekX\RestFn\Services\Auth\Identity;
use ArekX\RestFn\Services\Auth\IdentityService;
use tests\Services\Auth\_mock\DummyAuthenticatedAction;
use tests\Services\Auth\_mock\DummyPublicAction;
use tests\TestCase;

class ActionAuthorizationTest extends TestCase
{
    private Container $container;
    private Parser $parser;
    private IdentityService $identityService;

    protected function setUp(): void
    {
        $this->container = new Container([
            'aliases' => WebApp::DEFAULT_ALIASES,
            'config' => [
                'global' => [
                    'ops' => [RunOp::name() => RunOp::class],
                    'actions' => [
                        'secure' => DummyAuthenticatedAction::class,
                        'public' => DummyPublicAction::class,
                    ],
                ],
            ],
        ]);

        $this->parser = $this->container->make(Parser::class);
        // The same shared instance that RunOp reads from.
        $this->identityService = $this->container->make(IdentityService::class);
    }

    public function testPublicActionRunsWithoutAuthentication()
    {
        $result = $this->parser->evaluate(['run', 'public', []], new Context());

        $this->assertSame(['public' => true], $result);
    }

    public function testAuthenticatedActionIsRejectedWhenUnauthenticated()
    {
        $this->expectException(AuthenticationRequiredException::class);

        $this->parser->evaluate(['run', 'secure', []], new Context());
    }

    public function testAuthenticatedActionRunsWhenAuthenticated()
    {
        $this->identityService->setIdentity(new Identity(1));

        $result = $this->parser->evaluate(['run', 'secure', []], new Context());

        $this->assertSame(['secure' => true], $result);
    }
}
