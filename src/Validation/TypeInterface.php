<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation;

interface TypeInterface
{
    public static function name(): string;
    public static function fields(): array;
}