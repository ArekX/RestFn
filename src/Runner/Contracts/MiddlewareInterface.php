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

use ArekX\RestFn\Parser\Context;
use ArekX\RestFn\Runner\Request;

/**
 * Interface Middleware
 * @package ArekX\RestFn\Runner\Contracts
 *
 * Middleware wraps the handling of a request. Code that runs before calling
 * $next acts on the way in (for example authentication); code that runs after
 * acts on the way out (for example shaping the result). Not calling $next
 * short-circuits the request and returns early.
 */
interface MiddlewareInterface
{
    /**
     * Processes the request, optionally delegating to the next handler.
     *
     * @param Request $request The request being handled.
     * @param Context $context The per-evaluation context.
     * @param callable $next The next handler: fn(Request, Context): mixed.
     * @return mixed The result returned down the chain (or produced directly).
     */
    public function process(Request $request, Context $context, callable $next): mixed;
}
