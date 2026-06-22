<?php


namespace tests\Services\Auth\_mock;


use ArekX\RestFn\Services\Auth\Contracts\AuthenticatorInterface;
use ArekX\RestFn\Services\Auth\Contracts\IdentityInterface;

class StubAuthenticator implements AuthenticatorInterface
{
    public function __construct(
        private ?IdentityInterface $identity,
    ) {}

    public function authenticate(mixed $payload): ?IdentityInterface
    {
        return $this->identity;
    }
}
