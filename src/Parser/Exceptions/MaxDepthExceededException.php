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

namespace ArekX\RestFn\Parser\Exceptions;

use ArekX\RestFn\Contracts\ClientExceptionInterface;

/**
 * Class MaxDepthExceededException
 * @package ArekX\RestFn\Parser\Exceptions
 *
 * Thrown when an expression nests deeper than the parser's configured maximum
 * depth. Guards against stack exhaustion from deeply nested, client-supplied
 * expressions.
 */
class MaxDepthExceededException extends \Exception implements ClientExceptionInterface
{
    public int $maxDepth;

    public function __construct(int $maxDepth)
    {
        $this->maxDepth = $maxDepth;

        parent::__construct("Maximum expression depth of {$maxDepth} was exceeded.");
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function getClientDetails(): ?array
    {
        return null;
    }
}
