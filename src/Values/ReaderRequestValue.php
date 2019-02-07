<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Values;

use ArekX\JsonQL\Types\ReaderRequest;

class ReaderRequestValue extends TypedValue
{
    /**
     * @inheritdoc
     */
    public static function type(): string
    {
        return ReaderRequest::class;
    }
}