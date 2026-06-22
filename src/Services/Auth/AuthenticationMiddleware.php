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

use ArekX\RestFn\DI\Attributes\Config;
use ArekX\RestFn\Parser\Context;
use ArekX\RestFn\Runner\Contracts\MiddlewareInterface;
use ArekX\RestFn\Runner\Request;
use ArekX\RestFn\Services\Auth\Contracts\AuthenticatorInterface;
use ArekX\RestFn\Services\Auth\Contracts\IdentityServiceInterface;
use ArekX\RestFn\Services\Auth\Contracts\TokenParserInterface;

/**
 * Class AuthenticationMiddleware
 * @package ArekX\RestFn\Services\Auth
 *
 * Establishes the identity for a request from a bearer token. When a token is
 * present it is parsed and authenticated, and the resulting identity is stored
 * on the identity service. A request without a token stays unauthenticated; an
 * invalid token surfaces as the parser's exception.
 *
 * This middleware does not reject unauthenticated requests itself - that is
 * enforced per action via AuthenticatedActionInterface.
 */
class AuthenticationMiddleware implements MiddlewareInterface
{
    /**
     * @param TokenParserInterface $tokenParser Verifies the raw token.
     * @param AuthenticatorInterface $authenticator Maps the verified token to an identity.
     * @param IdentityServiceInterface $identityService Holds the resolved identity.
     * @param string $header Header the token is read from.
     * @param string $scheme Authorization scheme prefix.
     */
    public function __construct(
        public TokenParserInterface $tokenParser,
        public AuthenticatorInterface $authenticator,
        public IdentityServiceInterface $identityService,
        #[Config('auth.header', default: 'Authorization')]
        public string $header = 'Authorization',
        #[Config('auth.scheme', default: 'Bearer')]
        public string $scheme = 'Bearer',
    ) {}

    /**
     * @inheritDoc
     */
    #[\Override]
    public function process(Request $request, Context $context, callable $next): mixed
    {
        $token = $this->extractToken($request);

        if ($token !== null) {
            $payload = $this->tokenParser->parse($token);
            $this->identityService->setIdentity($this->authenticator->authenticate($payload));
        }

        return $next($request, $context);
    }

    /**
     * Extracts the bearer token from the request headers, or null when absent.
     *
     * @param Request $request
     * @return string|null
     */
    protected function extractToken(Request $request): ?string
    {
        $value = $request->headers[$this->header] ?? $request->headers[strtolower($this->header)] ?? null;

        if (!is_string($value)) {
            return null;
        }

        $prefix = $this->scheme . ' ';

        if (!str_starts_with($value, $prefix)) {
            return null;
        }

        return substr($value, strlen($prefix));
    }
}
