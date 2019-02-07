<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Validation\Fields;

use ArekX\JsonQL\Validation\BaseField;

/**
 * Class StringField Field representing a string type.
 * @package ArekX\JsonQL\Validation\Fields
 */
class StringField extends BaseField
{
    const ERROR_NOT_A_STRING = 'not_a_string';
    const ERROR_LESS_THAN_MIN_LENGTH = 'less_than_min_length';
    const ERROR_GREATER_THAN_MAX_LENGTH = 'greater_than_max_length';
    const ERROR_NOT_A_MATCH = 'not_a_match';

    /**
     * StringField constructor.
     * @param int|null $maxLength Maximum length of the string
     */
    public function __construct(?int $maxLength = null)
    {

        if ($maxLength !== null) {
            $this->max($maxLength);
        }
    }

    /**
     * Minimum value set.
     *
     * Defaults to null - means no value is set.
     *
     * @var null
     */
    public $min = null;

    /**
     * Maximum value set.
     *
     * Defaults to null - means no value is set.
     *
     * @var null
     */
    public $max = null;

    /**
     * Encoding used for getting character length.
     *
     * Defaults to null - mb_internal_encoding() is used.
     *
     * @var null
     */
    public $encoding = null;

    /**
     * Pattern which will be used to match a string against.
     *
     * Defaults to null - no pattern is used.
     *
     * @var null
     */
    public $match = null;

    /**
     * Sets minimum length to validate against.
     *
     * @param int $minimum Minimum length
     * @return $this
     */
    public function min(int $minimum)
    {
        $this->min = $minimum;

        if ($this->max !== null && $this->min > $this->max) {
            $this->max = $this->min;
        }

        return $this;
    }


    /**
     * Sets maximum length to validate against.
     *
     * @param int $maximum Maximum length
     * @return $this
     */
    public function max(int $maximum)
    {
        $this->max = $maximum;

        if ($this->min !== null && $this->min > $this->max) {
            $this->min = $this->max;
        }

        return $this;
    }

    /**
     * Sets encoding used for string length.
     *
     * @param string $encoding Encoding used for string length.
     * @return $this
     */
    public function encoding(?string $encoding = null)
    {
        $this->encoding = $encoding;
        return $this;
    }

    /**
     * Match string against a pattern.
     *
     * @param string $match Pattern which will be used.
     * @return $this
     */
    public function match(string $match)
    {
        $this->match = $match;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function name(): string
    {
        return 'string';
    }

    /**
     * @inheritdoc
     */
    protected function fieldDefinition(): array
    {
        return [
            'minLength' => $this->min,
            'maxLength' => $this->max,
            'encoding' => $this->encoding,
            'match' => $this->match
        ];
    }

    /**
     * @inheritdoc
     */
    protected function doValidate($value, $parentValue = null): array
    {
        if (!is_string($value)) {
            return [self::ERROR_NOT_A_STRING => true];
        }

        $errors = [];
        if ($this->min !== null && $this->getLength($value) < $this->min) {
            $errors[self::ERROR_LESS_THAN_MIN_LENGTH] = $this->min;
        }

        if ($this->max !== null && $this->getLength($value) > $this->max) {
            $errors[self::ERROR_GREATER_THAN_MAX_LENGTH] = $this->max;
        }

        if ($this->match !== null && !preg_match($this->match, $value)) {
            $errors[self::ERROR_NOT_A_MATCH] = $this->match;
        }

        return $errors;
    }

    protected function getLength($string)
    {
        if ($this->encoding !== null) {
            return mb_strlen($string, $this->encoding);
        }

        return mb_strlen($string);
    }
}