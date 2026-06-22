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

use ArekX\RestFn\Parser\Context;

/**
 * Interface RuleParser
 * @package ArekX\RestFn\Parser\Contracts
 *
 * Represents one rule parser.
 */
interface EvaluatorInterface
{
    /**
     * Validates rules sent rules against a value.
     *
     * @param array $value Value to be validated with rules.
     * @param Context $context Per-evaluation context.
     * @return null|array Returns list of errors if validation fails, or null if validation is successful.
     */
    public function validate(array $value, Context $context): ?array;

    /**
     * Evaluates a value.
     *
     * @param array $value Value to be evaluated.
     * @param Context $context Per-evaluation context.
     * @return mixed Returns a result from evaluated value.
     */
    public function evaluate(array $value, Context $context): mixed;
}
