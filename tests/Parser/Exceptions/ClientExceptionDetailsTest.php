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

namespace tests\Parser\Exceptions;

use ArekX\RestFn\Contracts\ClientExceptionInterface;
use ArekX\RestFn\Parser\Exceptions\InvalidEvaluation;
use ArekX\RestFn\Parser\Exceptions\InvalidOperation;
use ArekX\RestFn\Parser\Exceptions\InvalidValueFormatException;
use ArekX\RestFn\Parser\Exceptions\MaxDepthExceededException;
use ArekX\RestFn\Parser\Ops\ValueOp;
use tests\TestCase;

class ClientExceptionDetailsTest extends TestCase
{
    /**
     * The parser's request exceptions are client-facing and expose no extra
     * details, so the error handler may show their message safely.
     */
    public function testParserExceptionsAreClientFacingWithoutDetails()
    {
        $exceptions = [
            new InvalidOperation('foo'),
            new InvalidValueFormatException(),
            new MaxDepthExceededException(64),
            new InvalidEvaluation(new ValueOp(), 'bad'),
        ];

        foreach ($exceptions as $exception) {
            $this->assertInstanceOf(ClientExceptionInterface::class, $exception);
            $this->assertNull($exception->getClientDetails());
        }
    }
}
