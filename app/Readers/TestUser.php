<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace App\Readers;

use ArekX\JsonQL\Rest\Interfaces\ReaderInterface;

class TestUser implements ReaderInterface
{
    public function run(array $data)
    {
        return $data;
    }
}