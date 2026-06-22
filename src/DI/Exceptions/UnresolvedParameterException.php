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
 * Class UnresolvedParameterException
 * @package ArekX\RestFn\DI\Exceptions
 *
 * Thrown when a constructor parameter cannot be resolved: it has no override,
 * no Inject/Config attribute, no autowirable type, and no default value.
 */
class UnresolvedParameterException extends \Exception
{
    public string $className;
    public string $parameterName;

    public function __construct(string $className, string $parameterName)
    {
        $this->className = $className;
        $this->parameterName = $parameterName;

        parent::__construct(
            "Could not resolve constructor parameter \${$parameterName} for '{$className}'. "
            . 'Provide it as an override, add an Inject/Config attribute, give it an autowirable type, or a default value.',
        );
    }
}
