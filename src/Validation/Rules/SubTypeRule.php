<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Rules;


use ArekX\JsonQL\Config\Config;
use ArekX\JsonQL\Helpers\Value;
use ArekX\JsonQL\Types\TypeInterface;
use function ArekX\JsonQL\Validation\objectType;
use ArekX\JsonQL\Validation\RuleInterface;

class SubTypeRule extends BaseRule
{
    const SUBTYPE_INVALID = 'subtype_invalid';

    /** @var TypeInterface */
    protected $subType;

    protected $overrideFields = [];

    /** @var RuleInterface */
    protected $validator;

    public function __construct(TypeInterface $subType, Config $config)
    {
        $this->subType = is_object($subType) ? $subType : $config->get($config);
        $this->validator = $this->subType->getValidator();
    }

    public function override(?array $fields = null): SubTypeRule
    {
        if ($fields === null) {
            $this->validator = $this->subType->getValidator();
        } else {
            $this->validator = objectType(Value::merge(
                $this->subType->getValidator()->fields,
                $fields
            ));
        }
        return $this;
    }

    /**
     * Performs child field validation.
     *
     * @param string $field Field name
     * @param mixed $value Value to be validated.
     * @param array $data All other data to be validated.
     * @return array
     */
    protected function doValidate(string $field, $value, $data, $errors): array
    {
        $results = $this->validator->validate($field, $value, $data, $errors);

        if (!empty($results)) {
            $errors[] = ['type' => self::SUBTYPE_INVALID, 'data' => $results];
        }

        return $errors;
    }
}