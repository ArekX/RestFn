<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Helpers\Value;


use ArekX\JsonQL\Helpers\Value;

trait SetupFunction
{
    public function testSetupValuesWithEmptyConfig()
    {
        $ob = new \stdClass();

        Value::setup($ob, [], [
            'test' => 'default'
        ]);

        $this->assertEquals('default', $ob->test);
    }

    public function testSetupValuesWithExistingConfig()
    {
        $ob = new \stdClass();

        Value::setup($ob, ['test' => 'existing'], [
            'test' => 'default'
        ]);

        $this->assertEquals('existing', $ob->test);
    }
}