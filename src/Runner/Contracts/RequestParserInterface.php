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

namespace ArekX\RestFn\Runner\Contracts;

use ArekX\RestFn\Runner\Request;

/**
 * Interface RequestParser
 * @package ArekX\RestFn\Runner\Contracts
 *
 * Turns the raw incoming request into a Request object the runner can process.
 */
interface RequestParserInterface
{
    /**
     * Parses the incoming request.
     *
     * @return Request
     */
    public function parse(): Request;
}
