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

namespace ArekX\RestFn\DI\Attributes;

/**
 * Class Config
 * @package ArekX\RestFn\DI\Attributes
 *
 * Marks a property or constructor parameter to receive a configuration value.
 *
 * The key is a dot-path resolved against, in order: the class' per-class
 * override config, then the global config, then this attribute's default.
 *
 * @see \ArekX\RestFn\DI\Container::resolveConfigValue()
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_PARAMETER)]
class Config
{
    public string $key;
    public mixed $default;

    /**
     * @param string $key Dot-path of the configuration value (e.g. "limits.maxDepth").
     * @param mixed $default Value used when the key is absent from both override and global config.
     */
    public function __construct(string $key, mixed $default = null)
    {
        $this->key = $key;
        $this->default = $default;
    }
}
