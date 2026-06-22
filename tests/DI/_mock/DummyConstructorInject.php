<?php


namespace tests\DI\_mock;


use ArekX\RestFn\DI\Attributes\Inject;

class DummyConstructorInject
{
    public $explicit;
    public DummyClass $typed;

    public function __construct(
        #[Inject(DummyOverrideClass::class)] $explicit,
        #[Inject] DummyClass $typed,
    ) {
        $this->explicit = $explicit;
        $this->typed = $typed;
    }
}
