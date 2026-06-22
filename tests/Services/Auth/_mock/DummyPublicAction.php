<?php


namespace tests\Services\Auth\_mock;


use ArekX\RestFn\Parser\Contracts\ActionInterface;

class DummyPublicAction implements ActionInterface
{
    public function run(mixed $data): array
    {
        return ['public' => true];
    }
}
