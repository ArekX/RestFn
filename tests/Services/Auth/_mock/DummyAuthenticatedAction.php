<?php


namespace tests\Services\Auth\_mock;


use ArekX\RestFn\Parser\Contracts\ActionInterface;
use ArekX\RestFn\Services\Auth\Contracts\AuthenticatedActionInterface;

class DummyAuthenticatedAction implements ActionInterface, AuthenticatedActionInterface
{
    public function run(mixed $data): array
    {
        return ['secure' => true];
    }
}
