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
 * Interface Factory
 * @package ArekX\RestFn\DI\Contracts
 *
 * Classes which implement Factory will have create() called so that they
 * can handle resolution on what will be created.
 *
 * Classes will not be auto-wired unless instantiated through call to Injector::make()
 *
 * @see Injector::make()
 */
interface Factory
{
    /**
     * Resolve class creation
     *
     * @param Injector $injector Injector which called this function.
     * @param array $config Configuration for this class name set by Injector::configure()
     * @param string $class Class passed to Injector::createThroughFactory()
     * @param array $constructorArgs Constructor arguments passed to Injector::createThroughFactory()
     * @see Injector::configure() Which will result in Configuration passed to this function.
     * @see Injector::createThroughFactory() Which will end up calling this function
     * @return mixed Resolved instance of the class.
     */
    public static function create(Injector $injector, array $config, string $class, array $constructorArgs);
}