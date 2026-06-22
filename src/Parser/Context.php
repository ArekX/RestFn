<?php

declare(strict_types=1);


/**
 * Copyright 2025 Aleksandar Panic
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

namespace ArekX\RestFn\Parser;

use ArekX\RestFn\Helper\Value;

/**
 * Class Context
 * @package ArekX\RestFn\Parser
 *
 * Holds the per-evaluation state for a single run of the parser: the variable
 * bindings created by `var` and the current nesting depth.
 *
 * A Context is owned by the caller and passed into Parser::evaluate(). It can be
 * remade and re-evaluated as many times as needed; because no evaluation state
 * lives on the Parser, separate evaluations cannot leak state into one another.
 */
class Context
{
    /**
     * Variable bindings created and read by the `var` operation.
     *
     * @var array
     */
    protected array $variables;

    /**
     * Current nesting depth of the expression being processed.
     *
     * @var int
     */
    protected int $depth = 0;

    /**
     * @param array $variables Initial variable bindings, if any.
     */
    public function __construct(array $variables = [])
    {
        $this->variables = $variables;
    }

    /**
     * Returns all variable bindings.
     *
     * @return array
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * Returns a single variable binding, supporting dot-notation traversal.
     *
     * @param string $name
     * @param mixed $default Returned when the variable is not set.
     * @return mixed
     */
    public function getVariable(string $name, mixed $default = null): mixed
    {
        return Value::get($name, $this->variables, $default);
    }

    /**
     * Sets a variable binding.
     *
     * @param string $name
     * @param mixed $value
     */
    public function setVariable(string $name, mixed $value): void
    {
        $this->variables[$name] = $value;
    }

    /**
     * Increments the nesting depth.
     *
     * @internal Used by the evaluator to track recursion; not for use by operations.
     */
    public function enter(): void
    {
        $this->depth++;
    }

    /**
     * Decrements the nesting depth.
     *
     * @internal Used by the evaluator to track recursion; not for use by operations.
     */
    public function leave(): void
    {
        $this->depth--;
    }

    /**
     * Returns the current nesting depth.
     *
     * @internal Used by the evaluator to enforce the maximum depth option.
     * @return int
     */
    public function getDepth(): int
    {
        return $this->depth;
    }
}
