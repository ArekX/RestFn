<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace App\Readers;

use ArekX\JsonQL\Interfaces\ReaderInterface;
use ArekX\JsonQL\Values\DataProvider;

class TestUser implements ReaderInterface
{
    public function run(): array
    {
        return [
            'data' => 'test',
            'def' => DataProvider::definition()
        ];
    }
}