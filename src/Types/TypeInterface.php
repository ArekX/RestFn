<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Types;

use ArekX\JsonQL\Validation\RuleInterface;

interface TypeInterface
{
    public static function name(): string;
    public static function fields(): array;
    public static function resolvedFields(): array;
    public static function validator(): RuleInterface;
    public static function strictValidator(): RuleInterface;
}