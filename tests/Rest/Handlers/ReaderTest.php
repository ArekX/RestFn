<?php


/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

use ArekX\JsonQL\Rest\Handlers\Reader;

class ReaderTest extends \tests\Rest\TestCase
{
    public function testIsValidRequestType()
    {
        $this->assertEquals(Reader::requestType(), 'read');
    }

    public function testIsValidResponseType()
    {
        $writer = $this->getReader();
        $this->assertEquals($writer->responseType(), 'read');
    }

    public function testHandleEmptyData()
    {
        $writer = $this->getReader();
        $this->assertEquals($writer->handle([]), []);
    }

    protected function getReader(): Reader
    {
        return $this->di->get(Reader::class);
    }
}