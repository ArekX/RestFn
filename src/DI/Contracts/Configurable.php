<?php
/**
 * @author Aleksandar Panic
 * @link https://restfn.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\RestFn\DI\Contracts;

use ArekX\RestFn\DI\Injector;

/**
 * Interface Configurable
 * @package ArekX\RestFn\DI\Contracts
 *
 * Class which implement this interface will receive config from Injector::getConfig()
 *
 * @see Injector::getConfig()
 */
interface Configurable
{
    /**
     * Configurable constructor.
     *
     * @param array $config Constructor config from Injector::getConfig() for this resolved class.
     * @param array $constructorArgs Constructor arguments passed to Injector::make()
     * @see Injector::make()
     */
    public function __construct(array $config, array $constructorArgs);
}