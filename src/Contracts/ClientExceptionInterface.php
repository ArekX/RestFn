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

namespace ArekX\RestFn\Contracts;

/**
 * Interface ClientExceptionInterface
 * @package ArekX\RestFn\Contracts
 *
 * Marks an exception as safe to surface to the client. Its message describes a
 * problem with the request (bad input, failed validation, missing auth) rather
 * than an internal fault, so the error handler may show it even outside debug
 * mode. Exceptions that do not implement this are treated as internal and hidden
 * behind a generic message in production.
 */
interface ClientExceptionInterface extends \Throwable
{
    /**
     * Structured, client-safe details to include alongside the message, or null
     * when there is nothing extra to expose.
     *
     * @return array|null
     */
    public function getClientDetails(): ?array;
}
