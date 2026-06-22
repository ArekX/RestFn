<?php


namespace tests\DI\_mock;


class DummyCircularA
{
    public function __construct(
        public DummyCircularB $b,
    ) {}
}
