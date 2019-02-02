<?php

use ArekX\JsonQL\Rest\Handlers\Definer;

/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
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