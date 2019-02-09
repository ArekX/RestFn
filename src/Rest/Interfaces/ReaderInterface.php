<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Rest\Interfaces;


use ArekX\JsonQL\Values\InvalidValueException;

interface ReaderInterface
{
    /**
     * @param array $data
     * @return mixed
     * @throws InvalidValueException
     */
    public function run(array $data);
}