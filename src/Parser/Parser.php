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
use ArekX\RestFn\Parser\Contracts\Operation;
use ArekX\RestFn\Parser\Exceptions\InvalidOperation;
use ArekX\RestFn\Parser\Exceptions\InvalidValueFormatException;

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
     * Configures parser with data.
     *
     * @param array $config
     */
    public function configure(array $config)
    {
        /** @var Operation[] $ops */
        $ops = $config['ops'];

        $this->ops = [];

        foreach ($ops as $op) {
            $this->ops[$op::name()] = $op;
        }
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
     * Performs value validation.
     *
     * If a value is valid for evaluating null is returned,
     * otherwise errors are returned in nested format.
     *
     * @param mixed $value
     * @return array|null
     * @throws InvalidOperation
     * @throws InvalidValueFormatException
     */
    public function validate($value): ?array
    {
        if (empty($value)) {
            return null;
        }

        return $this->getOperation($value)->validate($this, $value);
    }

    /**
     * Returns operation based on a value
     *
     * @param $value
     * @return mixed
     * @throws InvalidOperation
     * @throws InvalidValueFormatException
     */
    protected function getOperation($value)
    {
        if (!is_array($value)) {
            throw new InvalidValueFormatException();
        }

        $ruleName = $value[0] ?? '';

        if (empty($this->ops[$ruleName])) {
            throw new InvalidOperation($ruleName);
        }

        $operationClass = $this->ops[$ruleName];
        return new $operationClass();
    }

    /**
     * Evaluates a value and returns a result.
     *
     * @param mixed $value
     * @return array|mixed
     * @throws InvalidOperation
     * @throws InvalidValueFormatException
     */
    public function evaluate($value)
    {
        if (empty($value)) {
            return [];
        }

        return $this->getOperation($value)->evaluate($this, $value);
    }
}