<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Values;

use ArekX\JsonQL\Interfaces\DefinitionInterface;
use ArekX\JsonQL\Validation\Helpers\Validator;
use ArekX\JsonQL\Helpers\Value;
use ArekX\JsonQL\Types\BaseType;

abstract class TypedValue implements \ArrayAccess, DefinitionInterface
{
    /** @var mixed */
    protected $data;

    /** @var BaseType */
    protected static $type;

    protected function __construct(array $data)
    {
        $this->setData($data);
    }

    public static function from(array $data)
    {
        return new static($data);
    }

    protected static function defaultValues()
    {
        return null;
    }

    public function setData(array $data)
    {
        $this->data = $data;
        $this->processType();
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    public function __isset($name)
    {
        return $this->has($name);
    }

    public function __unset($name)
    {
        $this->offsetUnset($name);
    }

    public function has($name)
    {
        if (array_key_exists($name, $this->data)) {
            return true;
        }

        if (!is_string($name) || strpos($name, '.') === -1) {
            return false;
        }

        $parts = explode('.', $name);
        $walker = &$this->data;

        foreach ($parts as $part) {
            if (!array_key_exists($part, $walker)) {
                return false;
            }

            $walker = &$walker[$part];
        }

        return true;
    }

    public function get($name, $defaultValue = null)
    {
        return Value::get($this->data, $name, $defaultValue);
    }

    public function set($name, $value)
    {
        Value::set($this->data, $name, $value);
        $this->processType();
    }

    protected function processType()
    {
        $defaultValues = static::defaultValues();

        if (!empty($defaultValues)) {
            $this->data = Value::merge(static::defaultValues(), $this->data);
        }

        Validator::ensure($this->data, static::$type);
    }

    public static function definition(): array
    {
        return [
            'type' => static::$type::definition(),
            'default' => static::defaultValues()
        ];
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
        $this->processType();
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

}