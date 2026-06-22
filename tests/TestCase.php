<?php

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

namespace tests;

use ArekX\RestFn\DI\Container;
use ArekX\RestFn\App\WebApp;
use ArekX\RestFn\Parser\Parser;

class TestCase extends \PHPUnit\Framework\TestCase
{
    public function getcontainer()
    {
        return new Container();
    }

    /**
     * Builds a parser through a container so the evaluator and any Config-driven
     * values are injected the same way they are at runtime.
     *
     * @param array $ops Operation classes to register. Keyed by name automatically.
     * @param array $parserConfig Additional per-class config for the parser (e.g. ['limits' => ['maxDepth' => 3]]).
     */
    protected function makeParser(array $ops = [], array $parserConfig = []): Parser
    {
        $container = new Container([
            'aliases' => WebApp::DEFAULT_ALIASES,
            'config' => [
                'global' => ['ops' => $this->opsMap($ops)] + $parserConfig,
            ],
        ]);

        return $container->make(Parser::class);
    }

    /**
     * Builds the name => class operation map the parser expects from a plain list
     * of operation classes, keying each by its own name().
     *
     * @param array $classes
     * @return array
     */
    protected function opsMap(array $classes): array
    {
        $map = [];

        foreach ($classes as $class) {
            $map[$class::name()] = $class;
        }

        return $map;
    }
}
