<?php
/**
 * @author Aleksandar Panic
 * @link https://restfn.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\RestFn\DI\Contracts;

/**
 * Interface SharedInstance
 * @package ArekX\RestFn\DI\Contracts
 *
 * Classes which implement this interface will be instantiated only once and then
 * shared across all other classes which implement Injectable.
 *
 * @see Injectable
 */
interface SharedInstance
{
}