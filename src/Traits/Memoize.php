<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Traits;

/**
 * Trait Memoize
 *
 * Trait to support memoization functions in classes.
 * Memoization is used to execute expensive operations only once.
 *
 * @package ArekX\JsonQL\Traits
 */
trait Memoize
{
    /**
     * @var array List of statically memoized values.
     */
    protected static $staticMemoizedValues = [];

    /**
     * @var array List of memoized values
     */
    protected $memoizedValues = [];

    /**
     * Memoize one value statically.
     *
     * Memoized values are run only once, and every next time only a result is returned.
     *
     * @param string $key Key to be used to check whether or not value is memoized.
     * @param callable $retriever Callable function used call expensive operation to retrieve the value.
     * @return mixed Result from $retriever or memoized value if exists.
     */
    protected static function staticMemoize(string $key, callable $retriever)
    {
        if (array_key_exists($key, static::$staticMemoizedValues)) {
            return static::$staticMemoizedValues[$key];
        }

        return static::$staticMemoizedValues[$key] = $retriever();
    }

    /**
     * Memoize one value.
     *
     * Memoized values are run only once, and every next time only a result is returned.
     *
     * @param string $key Key to be used to check whether or not value is memoized.
     * @param callable $retriever Callable function used call expensive operation to retrieve the value.
     * @return mixed Result from $retriever or memoized value if exists.
     */
    protected function memoize($key, callable $retriever)
    {
        if (array_key_exists($key, $this->memoizedValues)) {
            return $this->memoizedValues[$key];
        }

        return $this->memoizedValues[$key] = $retriever();
    }
}