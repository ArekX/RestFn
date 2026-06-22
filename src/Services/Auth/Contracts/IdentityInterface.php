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
 * Interface Identity
 * @package ArekX\RestFn\Services\Auth\Contracts
 *
 * Represents an authenticated identity. An application provides its own
 * implementation (returned by its AuthenticatorInterface).
 */
interface IdentityInterface
{
    /**
     * Returns the unique identifier of this identity.
     *
     * @return mixed
     */
    public function getId(): mixed;

    /**
     * Returns a value from the identity's data, or $default when it is absent.
     *
     * @param string $key Data key (dot-notation supported).
     * @param mixed $default Returned when the key is not present.
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;
}
