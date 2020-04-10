<?php
/**
 * @author Aleksandar Panic
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\RestFn\DI;


class Injector
{
    public function __construct(array $config = [])
    {
    }

    public function make($class, ...$args)
    {
        return new $class(...$args);
    }
}