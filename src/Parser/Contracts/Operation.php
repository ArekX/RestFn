<?php
/**
 * Copyright 2020 Aleksandar Panic
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
 * Interface Operation
 * @package ArekX\RestFn\Parser\Contracts
 *
 * Represents one rule operation.
 */
interface Operation
{
    /**
     * Validate rules and value.
     *
     * Validate a rule against a value and return errors if any.
     *
     * @param mixed $value Value to be validated.
     * @param Evaluator $evaluator Evaluator which created the operation.
     *
     * @return null|array Returns null if rules and value are valid, array of errors otherwise.
     */
    public function validate(Evaluator $evaluator, $value);

    /**
     * Evaluates rules against a value.
     *
     * @param mixed $value Value to be evaluated against.
     * @param Evaluator $evaluator Evaluator which created this rule.
     * @return mixed Evaluated result
     */
    public function evaluate(Evaluator $evaluator, $value);

    /**
     * Returns a name of the operation
     *
     * @return string
     */
    public static function name(): string;
}