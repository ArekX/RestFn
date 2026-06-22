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

namespace ArekX\RestFn\DI\Exceptions;

/**
 * Class CircularDependencyException
 * @package ArekX\RestFn\DI\Exceptions
 *
 * Thrown when auto-wiring detects a dependency cycle, e.g. class A requires
 * class B which (directly or transitively) requires class A again.
 */
class CircularDependencyException extends \Exception
{
    /**
     * @var string[] The chain of classes being resolved when the cycle was detected.
     */
    public array $chain;

    /**
     * @param string $class Class which would re-enter resolution.
     * @param string[] $chain Classes currently being resolved.
     */
    public function __construct(string $class, array $chain)
    {
        $this->chain = [...$chain, $class];

        parent::__construct('Circular dependency detected while resolving: ' . implode(' -> ', $this->chain));
    }
}
