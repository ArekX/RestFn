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

namespace ArekX\RestFn\Services\Auth\Contracts;

/**
 * Interface Authenticator
 * @package ArekX\RestFn\Services\Auth\Contracts
 *
 * Maps a verified token payload to an application identity. Implemented by the
 * application - for example loading a user by the token's subject claim.
 */
interface AuthenticatorInterface
{
    /**
     * Resolves an identity from a verified token payload.
     *
     * @param mixed $payload Verified payload produced by a TokenParserInterface.
     * @return IdentityInterface|null The identity, or null when none could be resolved.
     */
    public function authenticate(mixed $payload): ?IdentityInterface;
}
