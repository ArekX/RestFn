<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation;


interface TypeValidatorInterface
{
    public static function validator(): FieldInterface;
    public static function strictValidator(): FieldInterface;
}