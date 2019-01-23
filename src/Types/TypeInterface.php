<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Types;


interface TypeInterface
{
    public function fields(): array;

    public function toArray(): array;
}