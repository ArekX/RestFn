<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace App\Readers;

use ArekX\JsonQL\Services\ReaderInterface;
use ArekX\JsonQL\Values\DataProvider;

class TestUser implements ReaderInterface
{
    public function run(): array
    {
        return [
            'data' => 'test',
            'def' => DataProvider::definition(),
            'definition' => DataProvider::create([
                'pagination' => [
                    'page' => 1,
                    'size' => 10,
                    'total' => 200
                ],
                'sort' => [
                    'sorted_by' => ['test' => 'ascending'],
                    'allow_multisort' => true,
                    'sort_items' => ['test' => ['ascending', 'descending']]
                ],
                'items' => ['ss']
            ])
        ];
    }
}