<?php

declare(strict_types=1);

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

namespace ArekX\RestFn\Services\Auth;

use ArekX\RestFn\DI\Attributes\Config;
use ArekX\RestFn\Helper\Value;
use ArekX\RestFn\Services\Auth\Contracts\AuthenticatorInterface;
use ArekX\RestFn\Services\Auth\Contracts\IdentityInterface;

/**
 * Class ClaimsAuthenticator
 * @package ArekX\RestFn\Services\Auth
 *
 * Default authenticator that builds an Identity directly from a token's claims.
 * The id is read from a configurable claim, and a configurable list of further
 * claims is copied into the identity's data. Applications that need to load a
 * user from storage should provide their own AuthenticatorInterface instead.
 */
class ClaimsAuthenticator implements AuthenticatorInterface
{
    /**
     * @param string $idClaim Claim used as the identity id (dot-notation supported).
     * @param array $claims Additional claim names to copy into the identity data.
     */
    public function __construct(
        #[Config('auth.identity.idClaim', default: 'sub')]
        protected string $idClaim = 'sub',
        #[Config('auth.identity.claims', default: [])]
        protected array $claims = [],
    ) {}

    /**
     * @inheritDoc
     */
    #[\Override]
    public function authenticate(mixed $payload): ?IdentityInterface
    {
        if (!is_array($payload)) {
            return null;
        }

        $id = Value::get($this->idClaim, $payload);

        if ($id === null) {
            return null;
        }

        $data = [];

        foreach ($this->claims as $claim) {
            $data[$claim] = Value::get($claim, $payload);
        }

        return new Identity($id, $data);
    }
}
