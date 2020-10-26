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

namespace ArekX\RestFn\Parser;


use ArekX\RestFn\DI\Contracts\Configurable;
use ArekX\RestFn\DI\Contracts\Injectable;
use ArekX\RestFn\Parser\Contracts\Evaluator;
use ArekX\RestFn\Parser\Exceptions\InvalidOperation;
use ArekX\RestFn\Parser\Exceptions\InvalidRuleFormat;

/**
 * Class Parser
 * @package ArekX\RestFn\Parser
 *
 * This class represents a parser which is used to handle the requests.
 */
class Parser implements Injectable, Configurable, Evaluator
{
    /**
     * Operation handlers where each operation is mapped to a class
     *
     * @var array
     */
    public $ops = [];

    /**
     * Configures parser with data.
     *
     * @param array $config
     */
    public function configure(array $config)
    {
        $this->ops = $config['ops'] ?? [];
    }

    /**
     * Represents current context.
     *
     * Contexts is an arbitrary data which is is stored inside a Parser
     * purpose of the context is to have a centralized data store which is accessible
     * by all of the rules during evaluation or validation.
     *
     * @var array
     */
    protected $context = [];

    /**
     * Sets current context.
     *
     * Expected context for this parser is in array format.
     *
     * @param array $context
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * Returns currently set context.
     *
     * If there is no context set an empty array is returned.
     *
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }

    public function validate($rules, $value): ?array
    {
        return null;
    }

    public function evaluate($rules, $value)
    {
        if (!is_array($rules)) {
            throw new InvalidRuleFormat();
        }

        $ruleName = $rules[0] ?? '';

        if (empty($this->ops[$ruleName])) {
            throw new InvalidOperation($ruleName);
        }

        $operationClass = $this->ops[$ruleName];
        $operation = new $operationClass();

        return $operation->evaluate($rules, $value, $this);
    }
}