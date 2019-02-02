<?php
/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
 **/

namespace ArekX\JsonQL\Helpers;

/**
 * Class Value Helper for handling values.
 * @package ArekX\JsonQL\Helpers
 */
class Value
{

    /**
     * Merges one or more arrays into one.
     *
     * Each next array will recursively override the previous array if
     * it has the same keys.
     *
     * @param array ...$arrays Arrays which will be merged.
     * @return array Merged array.
     */
    public static function merge(array ...$arrays)
    {
        $result = [];

        foreach ($arrays as $array) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $result[$key] = static::merge($result[$key] ?? [], $value);
                } else {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
    }

    /**
     * Gets one value by a name from an array or an object.
     *
     * Value name can be in a dot notation.
     *
     * Example:
     * ```php
     * [
     *    'param' => [
     *       'name' => [
     *          'subname' => 'value'
     *       ]
     *    ]
     * ]
     * ```
     *
     * Can be accessed as: `param.name.subname`.
     *
     *
     * @param mixed $object Object to get a value from.
     * @param string $name Name of the value to be got.
     * @param null|mixed $default Default value to be returned if name is not set in an object.
     * @return mixed|null Value or default value.
     */
    public static function get($object, $name, $default = null)
    {
        if (!is_array($object) && !is_object($object)) {
            return $default;
        }

        $hasProperty =
            (is_array($object) && array_key_exists($name, $object)) ||
            (is_object($object) && property_exists($object, $name));

        if (strpos($name, '.') === -1 || $hasProperty) {
            return static::resolveValue($object, $name, $default);
        }

        $parts = explode('.', $name);
        $walker = &$object;

        $lastPart = array_pop($parts);

        foreach ($parts as $part) {
            if (is_array($walker) && array_key_exists($part, $walker)) {
                $walker = &$walker[$part];
            } elseif (is_object($walker) && property_exists($walker, $part)) {
                $walker = &$walker->{$part};
            } else {
                return $default;
            }
        }

        return static::resolveValue($walker, $lastPart, $default);
    }

    protected static function resolveValue(&$object, $name, $default = null)
    {
        if (is_array($object) && array_key_exists($name, $object)) {
            return $object[$name];
        }

        if (is_object($object) && property_exists($object, $name)) {
            return $object->{$name};
        }

        return $default;
    }

    public static function has($object, $name)
    {
        return
            (is_array($object) && array_key_exists($name, $object)) ||
            (is_object($object) && property_exists($object, $name));
    }

    public static function setup($object, $config, $defaultConfig)
    {
        foreach ($defaultConfig as $key => $defaultValue) {
            $object->{$key} = $config[$key] ?? $defaultValue;
        }
    }

    public static function isEmpty($value, $strict = true)
    {
        if ($strict) {
            return $value === "" ||
                $value === null ||
                $value === 0 ||
                $value === 0.00 ||
                $value === [];
        }

        return empty($value);
    }
}