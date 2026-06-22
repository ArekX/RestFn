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

use ArekX\RestFn\DI\Contracts\SharedInstanceInterface;
use ArekX\RestFn\Services\Auth\Contracts\IdentityInterface;
use ArekX\RestFn\Services\Auth\Contracts\IdentityServiceInterface;

/**
 * Class IdentityService
 * @package ArekX\RestFn\Services\Auth
 *
 * Default identity holder. It is a shared instance so the authentication
 * middleware and the operations that authorize actions observe the same
 * identity for the duration of the request.
 */
class IdentityService implements IdentityServiceInterface, SharedInstanceInterface
{
    protected ?IdentityInterface $identity = null;

    /**
     * @inheritDoc
     */
    #[\Override]
    public function getIdentity(): ?IdentityInterface
    {
        return $this->identity;
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function setIdentity(?IdentityInterface $identity): void
    {
        $this->identity = $identity;
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function isAuthenticated(): bool
    {
        return $this->identity !== null;
    }
}
