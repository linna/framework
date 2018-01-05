<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Helper;

/**
 * String Helper.
 */
class Str
{
    /**
     * Determine if a given string starts with a given substring.
     *
     * @param string $haystack
     * @param array  $needles  Array of string needles
     *
     * @return bool
     */
    public static function startsWith(string $haystack, array $needles = []) : bool
    {
        foreach ($needles as $needle) {
            if (strpos($haystack, (string) $needle) === 0) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Determine if a given string ends with a given substring.
     *
     * @param string $haystack
     * @param array  $needles  Array of string needles
     *
     * @return bool
     */
    public static function endsWith(string $haystack, array $needles = []) : bool
    {
        foreach ($needles as $needle) {
            if (strpos(strrev($haystack), (string) $needle) === 0) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Determine if a given string starts and ends with a given substring.
     *
     * @param string $haystack
     * @param array $needles
     * @return bool
     */
    public static function startsEndsWith(string $haystack, array $needles = []) : bool
    {
        foreach ($needles as $needle) {
            if (strpos($haystack, (string) $needle) === 0 && strpos(strrev($haystack), (string) $needle) === 0) {
                return true;
            }
        }
        
        return false;
    }
}
