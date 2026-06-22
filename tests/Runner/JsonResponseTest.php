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

namespace tests\Runner;

use ArekX\RestFn\Runner\JsonResponse;
use tests\TestCase;

class JsonResponseTest extends TestCase
{
    public function testEncodesResultAsJson()
    {
        $response = new JsonResponse();

        $this->assertSame('{"id":1,"name":"test"}', $response->respond(['id' => 1, 'name' => 'test']));
        $this->assertSame('42', $response->respond(42));
        $this->assertSame('"hello"', $response->respond('hello'));
        $this->assertSame('null', $response->respond(null));
        $this->assertSame('[1,2,3]', $response->respond([1, 2, 3]));
    }

    public function testThrowsOnUnencodableResult()
    {
        $this->expectException(\JsonException::class);

        // A resource cannot be JSON-encoded.
        (new JsonResponse())->respond(fopen('php://memory', 'r'));
    }
}
