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
        $this->assertEquals(Definer::getRequestType(), 'define');
    }

    public function testIsValidResponseType()
    {
        $writer = $this->getDefiner();
        $this->assertEquals($writer->getResponseType(), 'defined');
    }

    public function testHandleEmptyData()
    {
        $writer = $this->getDefiner();
        $this->assertEquals($writer->handle([]), []);
    }

    protected function getDefiner(): Definer
    {
        return $this->di->get(Definer::class);
    }
}