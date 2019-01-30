<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Traits\Handlers;

use ArekX\JsonQL\Traits\Memoize;

class MemoizeHandler
{
    use Memoize;

    public function memoizeMethod()
    {
        return $this->memoize(__METHOD__, function() {
            return rand(1, 500000);
        });
    }

    public static function memoizeStaticMethod()
    {
        return static::staticMemoize(__METHOD__, function() {
            return rand(1, 500000);
        });
    }
}