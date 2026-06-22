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

namespace ArekX\RestFn\Runner\Middleware;

use ArekX\RestFn\Contracts\ClientExceptionInterface;
use ArekX\RestFn\DI\Attributes\Config;
use ArekX\RestFn\Parser\Context;
use ArekX\RestFn\Runner\Contracts\MiddlewareInterface;
use ArekX\RestFn\Runner\Request;

/**
 * Class ErrorMiddleware
 * @package ArekX\RestFn\Runner\Middleware
 *
 * Outermost middleware: wraps the rest of the pipeline in a try/catch and turns
 * any thrown error into a structured response instead of letting it escape.
 *
 * What ends up in the response depends on the error and on debug mode:
 *  - Client errors (those implementing ClientExceptionInterface) always show
 *    their message and any client-safe details - they describe a bad request.
 *  - Internal errors are hidden behind a generic message in production so no
 *    implementation detail leaks. With debug enabled the real message is shown.
 *  - With debug enabled a `debug` block with the exception type, location and
 *    stack trace is added to every error.
 *
 * Place it first in the middleware list so it surrounds every other middleware.
 */
class ErrorMiddleware implements MiddlewareInterface
{
    /**
     * @param bool $debug When true, error responses include the real message and
     *                    a stack trace, from the 'runner.debug' config value.
     */
    public function __construct(
        #[Config('runner.debug', default: false)]
        protected bool $debug = false,
    ) {}

    /**
     * @inheritDoc
     */
    #[\Override]
    public function process(Request $request, Context $context, callable $next): mixed
    {
        try {
            return $next($request, $context);
        } catch (\Throwable $exception) {
            return $this->render($exception);
        }
    }

    /**
     * Builds the error response body for a caught exception.
     *
     * @param \Throwable $exception
     * @return array
     */
    protected function render(\Throwable $exception): array
    {
        $isClient = $exception instanceof ClientExceptionInterface;

        $body = [
            'error' => $isClient || $this->debug ? $exception->getMessage() : 'An unexpected error occurred.',
        ];

        if ($isClient && ($details = $exception->getClientDetails()) !== null) {
            $body['details'] = $details;
        }

        if ($this->debug) {
            $body['debug'] = [
                'type' => $exception::class,
                'location' => $exception->getFile() . ':' . $exception->getLine(),
                'trace' => explode("\n", $exception->getTraceAsString()),
            ];
        }

        return $body;
    }
}
