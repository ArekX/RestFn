<?php


namespace tests\DI\_mock;


use ArekX\RestFn\DI\Attributes\Config;

class DummyConstructorAutowire
{
    public DummyClass $service;
    public int $limit;
    public string $name;

    public function __construct(
        DummyClass $service,
        #[Config('limits.maxDepth', default: 5)] int $limit,
        string $name = 'default',
    ) {
        $this->service = $service;
        $this->limit = $limit;
        $this->name = $name;
    }
}
