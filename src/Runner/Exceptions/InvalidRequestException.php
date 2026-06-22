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

namespace ArekX\RestFn\Runner\Exceptions;

use ArekX\RestFn\Contracts\ClientExceptionInterface;

/**
 * Class InvalidRequestException
 * @package ArekX\RestFn\Runner\Exceptions
 *
 * Thrown when an incoming request cannot be processed: the body is not valid
 * JSON, or it fails validation. When it carries validation errors they are
 * available through $errors so the endpoint can return them to the client.
 */
class InvalidRequestException extends \Exception implements ClientExceptionInterface
{
    /**
     * @var array|null Nested validation errors, when the failure is a validation failure.
     */
    public ?array $errors;

    /**
     * @param string $message
     * @param array|null $errors Validation errors, if any.
     */
    public function __construct(string $message, ?array $errors = null)
    {
        $this->errors = $errors;

        parent::__construct($message);
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function getClientDetails(): ?array
    {
        return $this->errors;
    }
}
