<?php


namespace tests\DI\_mock;


class DummyCircularB
{
    public function __construct(
        public DummyCircularA $a,
    ) {}
}
