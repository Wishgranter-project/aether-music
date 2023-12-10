<?php
namespace AdinanCenci\AetherMusic\Helper;

class Validation 
{
    public static function is($data, string $expectedType) : bool
    {
        if ($expectedType == 'string[]') {
            return self::isArrayOf($data, 'is_string');
        }

        if ($expectedType == 'int[]') {
            return self::isArrayOf($data, 'is_int');
        }

        if ($expectedType == 'numeric[]') {
            return self::isArrayOf($data, 'is_numeric');
        }

        if ($expectedType == 'alphanumeric') {
            return self::isAlphanumeric($data);
        }

        if ($expectedType == 'alphanumeric[]') {
            return self::isArrayOf($data, [get_called_class(), 'isAlphanumeric']);
        }

        if ($expectedType == 'null') {
            return is_null($data);
        }

        return gettype($data) == $expectedType;
    }


    public static function isArrayOf($data, $function) : bool
    {
        if (! is_array($data)) {
            return false;
        }

        foreach ($data as $v) {
            if (! call_user_func($function, $v)) {
                return false;
            }
        }

        return true;
    }


    public static function isAlphanumeric($data) : bool
    {
        return is_string($data) || is_numeric($data);
    }
}
