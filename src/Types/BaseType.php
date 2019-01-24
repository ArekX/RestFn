<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Types;

use function ArekX\JsonQL\Validation\objectType;
use ArekX\JsonQL\Validation\RuleInterface;

abstract class BaseType implements TypeInterface
{
    /** @var RuleInterface */
    protected $validator;

    public function __construct()
    {
        $this->validator = objectType($this->fields());
    }

    public function validate(array $data): array
    {
        ['data' => $results] = $this->validator->validate('_', $data, ['_' => $data]);
        return $results;
    }

    public function getValidator(): RuleInterface
    {
        return $this->validator;
    }
}