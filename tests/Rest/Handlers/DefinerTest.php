<?php

use ArekX\JsonQL\Rest\Handlers\Definer;

/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

class DefinerTest extends \tests\Rest\TestCase
{
    public function testIsValidRequestType()
    {
        $this->assertEquals('define', Definer::requestType());
    }

    public function testIsValidResponseType()
    {
        $writer = $this->getDefiner();
        $this->assertEquals('defined', $writer->responseType());
    }

    public function testHandleEmptyData()
    {
        $writer = $this->getDefiner();
        $this->assertEquals([], $writer->handle([]));
    }

    protected function getDefiner(): Definer
    {
        return $this->di->get(Definer::class);
    }
}