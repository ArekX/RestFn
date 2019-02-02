<?php
/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
 **/

namespace tests\Config;

use ArekX\JsonQL\Rest\Config;
use DI\Container;
use Psr\Container\ContainerInterface;
use tests\TestCase;

class ConfigTest extends TestCase
{
    use GetParamFunction, ConstructorFunction, GetConfigsFunction;

    public function testGetDI()
    {
        $config = new Config();
        $this->assertInstanceOf(ContainerInterface::class, $config->getDI());
        $this->assertNotEquals($config->getDI(), $this->di);
    }

    public function testHasResolvesToDIHas()
    {
        $config = new Config();
        /** @var Container $di */
        $di = $config->getDI();
        $di->set('testingValue', 'Test value');
        $this->assertEquals($config->has('testingValue'), $di->has('testingValue'));
    }

    public function testGetResolvesToDIHas()
    {
        $config = new Config();
        /** @var Container $di */
        $di = $config->getDI();
        $di->set('testingValue', rand(1, 5000));
        $this->assertEquals($config->get('testingValue'), $di->get('testingValue'));
    }
}