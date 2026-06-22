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

namespace ArekX\RestFn\Runner;

use ArekX\RestFn\DI\Attributes\Config;
use ArekX\RestFn\Runner\Contracts\RequestParserInterface;
use ArekX\RestFn\Runner\Exceptions\InvalidRequestException;

/**
 * Class JsonRequestParser
 * @package ArekX\RestFn\Runner
 *
 * Reads the request body from an input stream and decodes it as JSON. By default
 * it reads from `php://input`; the stream is configurable so it can be pointed
 * elsewhere (for example in tests).
 */
class JsonRequestParser implements RequestParserInterface
{
    /**
     * @param string $inputStream Stream the request body is read from.
     */
    public function __construct(
        #[Config('runner.inputStream', default: 'php://input')] protected string $inputStream = 'php://input',
    ) {}

    /**
     * @inheritDoc
     * @throws InvalidRequestException When the body is not valid JSON.
     */
    #[\Override]
    public function parse(): Request
    {
        $raw = file_get_contents($this->inputStream);

        $headers = $this->readHeaders();

        if ($raw === false || $raw === '') {
            return new Request([], $headers);
        }

        try {
            $body = json_decode($raw, true, flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new InvalidRequestException('Request body is not valid JSON: ' . $exception->getMessage());
        }

        return new Request($body, $headers);
    }

    /**
     * Reads the incoming request headers, keyed by header name.
     *
     * @return array
     */
    protected function readHeaders(): array
    {
        if (function_exists('getallheaders')) {
            $headers = getallheaders();

            return $headers === false ? [] : $headers;
        }

        $headers = [];

        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $name = ucwords(strtolower(str_replace('_', ' ', substr($key, 5))));
                $headers[str_replace(' ', '-', $name)] = $value;
            }
        }

        return $headers;
    }
}
