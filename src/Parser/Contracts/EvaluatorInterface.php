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

namespace ArekX\RestFn\Parser\Contracts;

/**
 * Interface RuleParser
 * @package ArekX\RestFn\Parser\Contracts
 *
 * Represents one rule parser.
 */
interface EvaluatorInterface
{
    /**
     * Sets context for this evaluator.
     *
     * This is specific data which can be used by ops to help
     * resolve their rules.
     *
     * @param string $key Name of the value to set in current context.
     * @param mixed $value Value which will be set.
     * @return $this
     */
    public function setContext(string $key, mixed $value);

    /**
     * Returns currently set context data by key.
     *
     * @return mixed
     */
    public function getContext(string $key);

    /**
     * Validates rules sent rules against a value.
     *
     * @param array $value Value to be validated with rules.
     * @return null|array Returns list of errors if validation fails, or null if validation is successful.
     */
    public function validate(array $value): ?array;

    /**
     * Evaluates a value.
     *
     * @param array $value Value to be evaluated.
     * @return mixed Returns a result from evaluated value.
     */
    public function evaluate(array $value);
}
