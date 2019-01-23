<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Traits;


trait Memoize
{
    protected static $staticMemoizedValues = [];
    protected $memoizedValues = [];

    protected static function staticMemoize($key, callable $retriever)
    {
        if (array_key_exists($key, static::$staticMemoizedValues)) {
            return static::$staticMemoizedValues[$key];
        }

        return static::$staticMemoizedValues[$key] = $retriever();
    }

    protected function memoize($key, callable $retriever)
    {
        if (array_key_exists($key, $this->memoizedValues)) {
            return $this->memoizedValues[$key];
        }

        return $this->memoizedValues[$key] = $retriever();
    }
}