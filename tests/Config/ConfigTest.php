<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\Config;

use ArekX\JsonQL\Rest\Config;
use Auryn\Injector;
use tests\TestCase;

class ConfigTest extends TestCase
{
    use GetParamFunction, ConstructorFunction, GetConfigsFunction;

    public function testGetDI()
    {
        $config = new Config();
        $this->assertInstanceOf(Injector::class, $config->getDI());
        $this->assertNotEquals($config->getDI(), $this->di);
    }
}