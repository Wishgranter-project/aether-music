<?php

namespace WishgranterProject\AetherMusic\Helper;

/**
 * Collection of methods to help with validation.
 */
abstract class Validation
{
    /**
     * Checks if a value is of the expected type.
     *
     * It can also check an entire array.
     *
     * @param mixed $value
     *   The value to be checked.
     * @param string $ofTheExpectedType
     *   The type of data $value is supposed to be.
     * @return bool
     */
    public static function is($value, string $ofTheExpectedType): bool
    {
        if ($ofTheExpectedType == 'string[]') {
            return self::isArrayOf($value, 'is_string');
        }

        if ($ofTheExpectedType == 'int[]') {
            return self::isArrayOf($value, 'is_int');
        }

        if ($ofTheExpectedType == 'numeric[]') {
            return self::isArrayOf($value, 'is_numeric');
        }

        if ($ofTheExpectedType == 'alphanumeric') {
            return self::isAlphanumeric($value);
        }

        if ($ofTheExpectedType == 'alphanumeric[]') {
            return self::isArrayOf($value, [get_called_class(), 'isAlphanumeric']);
        }

        if ($ofTheExpectedType == 'null') {
            return is_null($value);
        }

        return gettype($value) == $ofTheExpectedType;
    }

    /**
     * Checks if all the elements of an array conform with specified type
     * validation.
     *
     * @param mixed $array
     *   The array which's value to check.
     * @param string $function
     *   The function to be used on the $array's elements.
     * @return bool
     */
    public static function isArrayOf($array, $function): bool
    {
        if (! is_array($array)) {
            return false;
        }

        foreach ($array as $v) {
            if (! call_user_func($function, $v)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks if a value is either a string or numeric.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public static function isAlphanumeric($value): bool
    {
        return is_string($value) || is_numeric($value);
    }
}
