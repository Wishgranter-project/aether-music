<?php

namespace WishgranterProject\AetherMusic\Helper;

abstract class Text
{
    /**
     * Basically substr_count but case insensitive.
     *
     * @param string $haystack
     *   The string to search in.
     * @param string|string[] $needles
     *   The substring to search for.
     *
     * @return int
     *   The number of occurences.
     */
    public static function substrCount(string $haystack, string $needle): int
    {
        return substr_count(strtolower($haystack), strtolower($needle));
    }

    /**
     * Count the number of substring occurrences.
     *
     * Basically substr_count but case insensitive and accept arrays
     * as well as strings.
     *
     * @param string $haystack
     *   The string to search in.
     * @param string|string[] $needles
     *   The substring(s) to search for.
     *
     * @return int
     *   The number of occurences.
     */
    public static function substrCountArray(string $haystack, $needles): int
    {
        $haystack = strtolower($haystack);
        $needles  = (array) $needles;

        $count = 0;
        foreach ($needles as $needle) {
            $count += self::substrCount($haystack, $needle);
        }

        return $count;
    }

    /**
     * Count the number of substring occurrences.
     *
     * Counts how many times string(s) from $needles occur inside strings from
     * $haystack.
     *
     * @param string|string[] $haystack
     *   The string(s) to search in.
     * @param string|string[] $needles
     *   The substring(s) to search for.
     *
     * @return int
     */
    public static function substrIntersect($haystack, $needles): int
    {
        $haystack  = (array) $haystack;
        $needles   = (array) $needles;

        $count = 0;

        foreach ($haystack as $hay) {
            $count += self::substrCountArray($hay, $needles);
        }

        return $count;
    }
}
