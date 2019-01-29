<?php

use ArekX\JsonQL\Rest\Handlers\Sequencer;

/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

class SequencerTest extends \tests\Rest\TestCase
{
    public function testIsValidRequestType()
    {
        $this->assertEquals(Sequencer::getRequestType(), 'sequence');
    }

    public function testIsValidResponseType()
    {
        $writer = $this->getSequencer();
        $this->assertEquals($writer->getResponseType(), 'sequenced');
    }

    public function testHandleEmptyData()
    {
        $writer = $this->getSequencer();
        $this->assertEquals($writer->handle([]), []);
    }

    protected function getSequencer(): Sequencer
    {
        return $this->di->get(Sequencer::class);
    }
}