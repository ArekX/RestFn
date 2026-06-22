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
 * Interface IdentityService
 * @package ArekX\RestFn\Services\Auth\Contracts
 *
 * Holds the identity authenticated for the current request. The authentication
 * middleware writes to it; operations read from it to authorize actions.
 */
interface IdentityServiceInterface
{
    /**
     * Returns the current identity, or null when the request is unauthenticated.
     *
     * @return IdentityInterface|null
     */
    public function getIdentity(): ?IdentityInterface;

    /**
     * Sets the current identity.
     *
     * @param IdentityInterface|null $identity
     */
    public function setIdentity(?IdentityInterface $identity): void;

    /**
     * Whether an identity is currently set.
     *
     * @return bool
     */
    public function isAuthenticated(): bool;
}
