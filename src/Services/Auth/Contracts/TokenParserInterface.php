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

use ArekX\RestFn\Services\Auth\Exceptions\InvalidTokenException;

/**
 * Interface TokenParser
 * @package ArekX\RestFn\Services\Auth\Contracts
 *
 * Resolves a raw token string into a verified payload. The authentication
 * middleware passes the verified payload to the AuthenticatorInterface.
 */
interface TokenParserInterface
{
    /**
     * Parses and verifies a raw token, returning its payload.
     *
     * @param string $token Raw token string (for example a JWT).
     * @return mixed The verified payload (for example JWT claims).
     * @throws InvalidTokenException When the token is malformed, unverifiable, or expired.
     */
    public function parse(string $token): mixed;
}
