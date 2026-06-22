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
use ArekX\RestFn\Services\Auth\Contracts\TokenParserInterface;
use ArekX\RestFn\Services\Auth\Exceptions\InvalidTokenException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;

/**
 * Class JwtTokenParser
 * @package ArekX\RestFn\Services\Auth
 *
 * Verifies a JWT and returns its claims, backed by the firebase/php-jwt library.
 * The expected algorithm is pinned (so an attacker cannot downgrade it), and any
 * verification failure is surfaced as an InvalidTokenException.
 */
class JwtTokenParser implements TokenParserInterface
{
    /**
     * @param string $secret Secret/key used to verify the signature.
     * @param string $algorithm Algorithm the token must be signed with.
     */
    public function __construct(
        #[\SensitiveParameter]
        #[Config('auth.jwt.secret', default: '')]
        protected string $secret = '',
        #[Config('auth.jwt.algorithm', default: 'HS256')]
        protected string $algorithm = 'HS256',
    ) {}

    /**
     * @inheritDoc
     * @return array The token claims.
     */
    #[\Override]
    public function parse(#[\SensitiveParameter] string $token): mixed
    {
        if ($this->secret === '') {
            throw new \RuntimeException('JWT secret is not configured (auth.jwt.secret).');
        }

        try {
            $claims = JWT::decode($token, new Key($this->secret, $this->algorithm));
        } catch (
            ExpiredException|SignatureInvalidException|BeforeValidException|\UnexpectedValueException|\DomainException|\InvalidArgumentException $exception
        ) {
            throw new InvalidTokenException('Token could not be verified: ' . $exception->getMessage(), 0, $exception);
        }

        return (array) $claims;
    }
}
