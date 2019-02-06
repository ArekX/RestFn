<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
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
     *          'name' => 'value'
     *       ]
     *    ]
     * ]
     * ```
     *
     * Can be accessed as: `param.name.name`
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

    /**
     * Resolves a value from a object.
     *
     * Object can be array or an instance of a class.
     *
     * @param array|object $object Object from which value will be resolved.
     * @param string $name Name of the value to be resolved.
     * @param null|mixed $default Default value to be returned.
     * @return mixed|null
     */
    protected static function resolveValue(&$object, string $name, $default = null)
    {
        if (is_array($object) && array_key_exists($name, $object)) {
            return $object[$name];
        }

        if (is_object($object) && property_exists($object, $name)) {
            return $object->{$name};
        }

        return $default;
    }

    /**
     * Setups an object from a configuration array.
     *
     * Config array is used to setup the object.
     * Default config is array is used to return the default value if the key
     * is not in the config array.
     *
     * Only public values can be set in an object.
     *
     * @param object $object Object which will be set.
     * @param array $config Config which will be used to set actual values.
     * @param array $defaultConfig Config which will be be used to return default value if the key is not in actual config.
     */
    public static function setup($object, $config, $defaultConfig)
    {
        foreach ($defaultConfig as $key => $defaultValue) {
            $object->{$key} = $config[$key] ?? $defaultValue;
        }
    }
}