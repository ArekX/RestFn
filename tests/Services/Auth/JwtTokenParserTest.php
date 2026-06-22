<?php

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

namespace tests\Services\Auth;

use ArekX\RestFn\Services\Auth\Exceptions\InvalidTokenException;
use ArekX\RestFn\Services\Auth\JwtTokenParser;
use Firebase\JWT\JWT;
use tests\TestCase;

class JwtTokenParserTest extends TestCase
{
    // firebase/php-jwt requires the HMAC key to be at least the hash size, so use a 64-byte key.
    private const SECRET = '0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef';
    private const OTHER_SECRET = 'fedcba9876543210fedcba9876543210fedcba9876543210fedcba9876543210';

    private function encode(array $claims, string $secret = self::SECRET, string $algorithm = 'HS256'): string
    {
        return JWT::encode($claims, $secret, $algorithm);
    }

    public function testParsesValidTokenIntoClaims()
    {
        $token = $this->encode(['sub' => 42, 'role' => 'admin', 'exp' => time() + 3600]);

        $claims = (new JwtTokenParser(self::SECRET))->parse($token);

        $this->assertSame(42, $claims['sub']);
        $this->assertSame('admin', $claims['role']);
    }

    public function testExpiredTokenThrows()
    {
        $token = $this->encode(['sub' => 1, 'exp' => time() - 10]);

        $this->expectException(InvalidTokenException::class);

        (new JwtTokenParser(self::SECRET))->parse($token);
    }

    public function testWrongSecretThrows()
    {
        $token = $this->encode(['sub' => 1, 'exp' => time() + 3600], self::OTHER_SECRET);

        $this->expectException(InvalidTokenException::class);

        (new JwtTokenParser(self::SECRET))->parse($token);
    }

    public function testWrongAlgorithmThrows()
    {
        $token = $this->encode(['sub' => 1, 'exp' => time() + 3600], self::SECRET, 'HS384');

        $this->expectException(InvalidTokenException::class);

        (new JwtTokenParser(self::SECRET, 'HS256'))->parse($token);
    }

    public function testMalformedTokenThrows()
    {
        $this->expectException(InvalidTokenException::class);

        (new JwtTokenParser(self::SECRET))->parse('not-a-jwt');
    }

    public function testMissingSecretThrows()
    {
        $this->expectException(\RuntimeException::class);

        (new JwtTokenParser(''))->parse('whatever');
    }
}
