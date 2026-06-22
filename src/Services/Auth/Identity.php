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

use ArekX\RestFn\Helper\Value;
use ArekX\RestFn\Services\Auth\Contracts\IdentityInterface;

/**
 * Class Identity
 * @package ArekX\RestFn\Services\Auth
 *
 * A simple identity carrying an id and a bag of data (for example token claims).
 * Applications may use this directly or provide their own IdentityInterface
 * implementation.
 */
class Identity implements IdentityInterface
{
    /**
     * @param mixed $id Unique identifier of the identity.
     * @param array $data Arbitrary identity data (for example roles or claims).
     */
    public function __construct(
        public mixed $id,
        public array $data = [],
    ) {}

    /**
     * @inheritDoc
     */
    #[\Override]
    public function getId(): mixed
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function get(string $key, mixed $default = null): mixed
    {
        return Value::get($key, $this->data, $default);
    }
}
