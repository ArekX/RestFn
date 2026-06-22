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

use ArekX\RestFn\Services\Auth\ClaimsAuthenticator;
use tests\TestCase;

class ClaimsAuthenticatorTest extends TestCase
{
    public function testBuildsIdentityFromDefaultSubClaim()
    {
        $identity = (new ClaimsAuthenticator())->authenticate(['sub' => 42]);

        $this->assertSame(42, $identity->getId());
    }

    public function testReadsConfiguredIdClaim()
    {
        $identity = (new ClaimsAuthenticator(idClaim: 'userId'))->authenticate(['userId' => 7]);

        $this->assertSame(7, $identity->getId());
    }

    public function testCopiesConfiguredClaimsIntoIdentityData()
    {
        $authenticator = new ClaimsAuthenticator(idClaim: 'sub', claims: ['email', 'role']);

        $identity = $authenticator->authenticate([
            'sub' => 1,
            'email' => 'user@example.com',
            'role' => 'admin',
            'secret' => 'not-copied',
        ]);

        $this->assertSame('user@example.com', $identity->get('email'));
        $this->assertSame('admin', $identity->get('role'));
        // Claims not in the configured list are not exposed.
        $this->assertNull($identity->get('secret'));
    }

    public function testReturnsNullWhenIdClaimMissing()
    {
        $identity = (new ClaimsAuthenticator())->authenticate(['email' => 'user@example.com']);

        $this->assertNull($identity);
    }

    public function testReturnsNullForNonArrayPayload()
    {
        $this->assertNull((new ClaimsAuthenticator())->authenticate('not-an-array'));
    }
}
