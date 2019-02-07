<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Values;

use ArekX\JsonQL\Traits\Memoize;
use ArekX\JsonQL\Types\BaseType;
use ArekX\JsonQL\Validation\Fields\ObjectField;
use ArekX\JsonQL\Validation\TypeInterface;

/**
 * Class TypedValue Class representing a strong validated value by a specified type.
 *
 * Type must be specified by settings TYPE constant on the value.
 *
 * @package ArekX\JsonQL\Values
 */
abstract class TypedValue
{
    use Memoize;

    /**
     * Data which of the type.
     * @var array
     */
    protected $data;

    /**
     * TypedValue constructor.
     *
     * Data passed are checked by a type validator.
     *
     * @param array $data Data which will be wrapped.
     */
    protected function __construct(array $data)
    {
        $this->setData($data);
    }

    /**
     * Creates new instance from array data.
     *
     * Array data are validated by the type.
     *
     * @param array $data Data to be passed.
     * @return TypedValue New Instance of typed value which holds the data.
     */
    public static function from(array $data)
    {
        return new static($data);
    }

    /**
     * Sets data and performs validation.
     *
     * @param array $data Data to be set.
     * @throws InvalidValueException Validation error thrown if data set is not valid to the type.
     */
    public function setData(array $data)
    {
        $this->data = $data;
        $this->validate();
    }

    /**
     * Returns data this class contains.
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Returns one item from data by name.
     *
     * @param string $name
     * @param null $defaultValue
     * @return mixed|null
     */
    public function get(string $name, $defaultValue = null)
    {
        return $this->data[$name] ?? $defaultValue;
    }

    /**
     * Sets one value by name.
     *
     * @param string $name Name of the property to be set.
     * @param mixed $value Value to be set.
     * @throws InvalidValueException
     */
    public function set(string $name, $value)
    {
        $this->data[$name] = $value;
        $this->validate();
    }

    /**
     * Returns iterator for the data.
     *
     * @return \Generator
     */
    public function iterate()
    {
        foreach ($this->data as $key => $value) {
            yield $key => $value;
        }
    }

    /**
     * Performs validation of the data.
     *
     * If the data is not valid error is thrown.
     *
     * @throws InvalidValueException
     */
    protected function validate()
    {
        $errors = static::getValidator()->validate($this->data);

        if (!empty($errors)) {
            throw new InvalidValueException($errors);
        }
    }

    /**
     * Returns type of the value.
     *
     * Returned class must implement TypeInterface.
     *
     * @see BaseType
     * @see TypeInterface
     * @return string|BaseType Class type.
     */
    public abstract static function type(): string;


    /**
     * Returns a validator object for this type.
     *
     * @return ObjectField
     */
    protected static function getValidator(): ObjectField
    {
        return static::staticMemoize(static::class . __METHOD__, function () {
            return static::type()::field();
        });
    }
}