<?php


namespace tests\DI\_mock;


use ArekX\RestFn\DI\Attributes\Config;
use ArekX\RestFn\DI\Attributes\Inject;

class DummyConfigInjectable
{
    public function __construct(
        #[Inject(DummyClass::class)] public $explicitObject,
        #[Inject] public DummyClass $typedObject,
        #[Config('limits.maxDepth', default: 10)] public int $maxDepth = 10,
        #[Config('missing.key', default: 'fallback')] public string $fallback = 'fallback',
        #[Config('limits.flag', default: true)] public bool $flag = true,
    ) {}
}
