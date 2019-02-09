<?php

use ArekX\JsonQL\Rest\Handlers\Writer;

/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

class WriterTest extends \tests\Rest\TestCase
{
    public function testIsValidRequestType()
    {
        $this->assertEquals('write', Writer::requestType());
    }

    public function testIsValidResponseType()
    {
        $writer = $this->getWriter();
        $this->assertEquals('wrote', $writer->responseType());
    }

    public function testHandleEmptyData()
    {
        $writer = $this->getWriter();
        $this->assertEquals([], $writer->handle([]));
    }

    protected function getWriter(): Writer
    {
        return $this->di->make(Writer::class);
    }
}