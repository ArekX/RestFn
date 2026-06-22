<?php


namespace tests\DI\_mock;


use ArekX\RestFn\DI\Container;

class DummyContainerAware
{
    public function __construct(
        public Container $container,
    ) {}
}
