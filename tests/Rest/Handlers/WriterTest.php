<?php

use ArekX\JsonQL\Rest\Handlers\Writer;

/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

class WriterTest extends \tests\Rest\TestCase
{
    public function testIsValidRequestType()
    {
        $this->assertEquals(Writer::getRequestType(), 'write');
    }

    public function testIsValidResponseType()
    {
        $writer = $this->getWriter();
        $this->assertEquals($writer->getResponseType(), 'wrote');
    }

    public function testHandleEmptyData()
    {
        $writer = $this->getWriter();
        $this->assertEquals($writer->handle([]), []);
    }

    protected function getWriter(): Writer
    {
        return $this->di->get(Writer::class);
    }
}