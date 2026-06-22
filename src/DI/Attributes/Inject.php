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

namespace ArekX\RestFn\DI\Attributes;

/**
 * Class Inject
 * @package ArekX\RestFn\DI\Attributes
 *
 * Marks a property or constructor parameter to be resolved as an object by the
 * container. When $definition is given that class/alias is created, otherwise
 * the declared type of the property or parameter is used.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_PARAMETER)]
class Inject
{
    public ?string $definition;

    /**
     * @param string|null $definition Class or alias to create. Null uses the declared type.
     */
    public function __construct(?string $definition = null)
    {
        $this->definition = $definition;
    }
}
