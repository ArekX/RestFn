<?php


/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
 **/

use ArekX\JsonQL\Rest\Handlers\Reader;

class ReaderTest extends \tests\Rest\TestCase
{
    public function testIsValidRequestType()
    {
        $this->assertEquals('read', Reader::requestType());
    }

    public function testIsValidResponseType()
    {
        $writer = $this->getReader();
        $this->assertEquals('read', $writer->responseType());
    }

    public function testHandleEmptyData()
    {
        $writer = $this->getReader();
        $this->assertEquals([], $writer->handle([]));
    }

    protected function getReader(): Reader
    {
        return $this->di->get(Reader::class);
    }
}