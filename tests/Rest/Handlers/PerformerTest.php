<?php


/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

use ArekX\JsonQL\Rest\Handlers\Performer;

class PerformerTest extends \tests\Rest\TestCase
{
    public function testIsValidRequestType()
    {
        $this->assertEquals('perform', Performer::requestType());
    }

    public function testIsValidResponseType()
    {
        $writer = $this->getPerformer();
        $this->assertEquals('performed', $writer->responseType());
    }

    public function testHandleEmptyData()
    {
        $writer = $this->getPerformer();
        $this->assertEquals([], $writer->handle([]));
    }

    protected function getPerformer(): Performer
    {
        return $this->di->get(Performer::class);
    }
}