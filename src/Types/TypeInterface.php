<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Types;

use ArekX\JsonQL\Validation\Rules\ObjectRule;

interface TypeInterface
{
    public static function name(): string;
    public static function fields(): array;
    public static function resolvedFields(): array;
    public static function getValidator(): ObjectRule;
    public static function validate(array $data): array;
}