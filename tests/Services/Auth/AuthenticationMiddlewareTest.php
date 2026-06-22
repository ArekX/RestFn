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

use ArekX\RestFn\Parser\Context;
use ArekX\RestFn\Runner\Request;
use ArekX\RestFn\Runner\Middleware\AuthenticationMiddleware;
use ArekX\RestFn\Services\Auth\Identity;
use ArekX\RestFn\Services\Auth\IdentityService;
use tests\Services\Auth\_mock\StubAuthenticator;
use tests\Services\Auth\_mock\StubTokenParser;
use tests\TestCase;

class AuthenticationMiddlewareTest extends TestCase
{
    protected function makeMiddleware(IdentityService $identityService, ?Identity $identity): AuthenticationMiddleware
    {
        return new AuthenticationMiddleware(
            tokenParser: new StubTokenParser(['sub' => 1]),
            authenticator: new StubAuthenticator($identity),
            identityService: $identityService,
        );
    }

    protected function passThrough(): callable
    {
        return static fn(Request $request, Context $context): mixed => 'handled';
    }

    public function testAuthenticatesWhenBearerTokenIsPresent()
    {
        $identityService = new IdentityService();
        $identity = new Identity(1);
        $middleware = $this->makeMiddleware($identityService, $identity);

        $request = new Request([], ['Authorization' => 'Bearer the-token']);

        $result = $middleware->process($request, new Context(), $this->passThrough());

        $this->assertSame('handled', $result);
        $this->assertTrue($identityService->isAuthenticated());
        $this->assertSame($identity, $identityService->getIdentity());
    }

    public function testStaysUnauthenticatedWithoutAToken()
    {
        $identityService = new IdentityService();
        $middleware = $this->makeMiddleware($identityService, new Identity(1));

        $request = new Request([], []);

        $middleware->process($request, new Context(), $this->passThrough());

        $this->assertFalse($identityService->isAuthenticated());
    }

    public function testIgnoresNonBearerAuthorization()
    {
        $identityService = new IdentityService();
        $middleware = $this->makeMiddleware($identityService, new Identity(1));

        $request = new Request([], ['Authorization' => 'Basic dXNlcjpwYXNz']);

        $middleware->process($request, new Context(), $this->passThrough());

        $this->assertFalse($identityService->isAuthenticated());
    }
}
