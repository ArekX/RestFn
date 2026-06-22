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

use ArekX\RestFn\Services\Auth\Identity;
use ArekX\RestFn\Services\Auth\IdentityService;
use tests\TestCase;

class IdentityServiceTest extends TestCase
{
    public function testStartsUnauthenticated()
    {
        $service = new IdentityService();

        $this->assertFalse($service->isAuthenticated());
        $this->assertNull($service->getIdentity());
    }

    public function testHoldsAnIdentity()
    {
        $service = new IdentityService();
        $identity = new Identity(7, ['role' => 'admin']);

        $service->setIdentity($identity);

        $this->assertTrue($service->isAuthenticated());
        $this->assertSame($identity, $service->getIdentity());
        $this->assertSame(7, $service->getIdentity()->getId());
        $this->assertSame('admin', $service->getIdentity()->get('role'));
        $this->assertNull($service->getIdentity()->get('missing'));
    }

    public function testCanBeCleared()
    {
        $service = new IdentityService();
        $service->setIdentity(new Identity(1));

        $service->setIdentity(null);

        $this->assertFalse($service->isAuthenticated());
    }
}
