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
    public function fields(): array;
    public function validate(array $data): array;
    public function getValidator(): ObjectRule;
}