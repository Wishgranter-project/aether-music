<?php 
namespace AdinanCenci\AetherMusic\Helper;

abstract class Text 
{
    /**
     * Basically substr_count but case insensitive and accept arrays.
     *
     * @param string $haystack
     *
     * @param string|array $needles
     *   The terms to search in $haystack.
     *
     * @return int
     *   The number of occurences.
     */
    public static function substrCount(string $haystack, $needles) : int
    {
        $needles = (array) $needles;

        $count = 0;
        foreach ($needles as $needle) {
            $count += substr_count(strtolower($haystack), strtolower($needle));
        }

        return $count;
    }
}
