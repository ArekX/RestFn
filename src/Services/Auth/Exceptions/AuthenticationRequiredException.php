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

namespace ArekX\RestFn\Services\Auth\Exceptions;

use ArekX\RestFn\Contracts\ClientExceptionInterface;

/**
 * Class AuthenticationRequiredException
 * @package ArekX\RestFn\Services\Auth\Exceptions
 *
 * Thrown when an action that requires authentication is run without an
 * authenticated identity.
 */
class AuthenticationRequiredException extends \Exception implements ClientExceptionInterface
{
    public string $action;

    public function __construct(string $action)
    {
        $this->action = $action;

        parent::__construct("Action '{$action}' requires authentication.");
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function getClientDetails(): ?array
    {
        return ['action' => $this->action];
    }
}
