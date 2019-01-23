<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace App\Readers;

use ArekX\JsonQL\Services\ReaderInterface;

class TestUser implements ReaderInterface
{
    public function run(): array
    {
        return ['data' => 'test'];
    }
}