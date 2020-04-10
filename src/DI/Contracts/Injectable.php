<?php
/**
 * @author Aleksandar Panic
 * @link https://restfn.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\RestFn\DI\Contracts;


/**
 * Interface Injectable
 * @package ArekX\RestFn\DI\Contracts
 *
 * Classes which implement injectable will have all their public typed classes auto-wired.
 *
 * Properties must be public and have a type set to be injected.
 *
 * All properties will be injected before __construct() is called.
 */
interface Injectable
{
}