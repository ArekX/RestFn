<?php


namespace tests\App\_mock;


use ArekX\RestFn\App\Contracts\ApplicationInterface;

class StubApp implements ApplicationInterface
{
    public function run(): mixed
    {
        return 'custom-app-result';
    }
}
