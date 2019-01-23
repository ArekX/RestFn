<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Rules;

use ArekX\JsonQL\Validation\RuleInterface;

class AndRule extends BaseRule
{
    /** @var RuleInterface[] */
    protected $childRules = [];

    public function __construct(array $childRules)
    {
        $this->childRules = $childRules;
    }

    /**
     * @inheritdoc
     */
    protected function doValidate(string $field, $value, $data, $errors): array
    {
        foreach ($this->childRules as $childRule) {
            $childErrors = $childRule->validate($field, $value, $data);

            if (empty($childErrors)) {
                continue;
            }

            return array_merge($errors, $childErrors);
        }

        return $errors;
    }
}