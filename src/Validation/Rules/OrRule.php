<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Rules;

use ArekX\JsonQL\Validation\RuleInterface;

class OrRule extends BaseRule
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
        $allChildrenErrors = [];

        foreach ($this->childRules as $childRule) {
            $childErrors = $childRule->validate($field, $value, $data);

            if (empty($childErrors)) {
                return $errors;
            }

            $allChildrenErrors = array_merge($allChildrenErrors, $childErrors);
        }

        return $errors;
    }
}