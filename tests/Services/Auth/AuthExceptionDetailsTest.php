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

use ArekX\RestFn\Contracts\ClientExceptionInterface;
use ArekX\RestFn\Services\Auth\Exceptions\AuthenticationRequiredException;
use ArekX\RestFn\Services\Auth\Exceptions\InvalidTokenException;
use tests\TestCase;

class AuthExceptionDetailsTest extends TestCase
{
    public function testAuthenticationRequiredExposesTheAction()
    {
        $exception = new AuthenticationRequiredException('secure');

        $this->assertInstanceOf(ClientExceptionInterface::class, $exception);
        $this->assertSame(['action' => 'secure'], $exception->getClientDetails());
    }

    public function testInvalidTokenIsClientFacingWithoutDetails()
    {
        $exception = new InvalidTokenException('bad token');

        $this->assertInstanceOf(ClientExceptionInterface::class, $exception);
        $this->assertNull($exception->getClientDetails());
    }
}
