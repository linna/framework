<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

namespace Linna\Helper;

use Closure;

/**
 * Env Helper.
 */
class Env
{

    /**
     * @var array Matches for particula values
     */
    private static $valuesMatches = [
        'true' => true,
        '(true)' => true,
        'false' => false,
        '(false)' => false,
        'empty' => '',
        '(empty)' => '',
        'null' => null,
        '(null)' => null,
    ];
    
    /**
     * Return value or the returned value if function is passed.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    private static function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }

    /**
     * Gets the value of an environment variable.
     *
     * @param string $key
     * @param mixed  $default
     *
     * return mixed
     */
    public static function get(string $key, $default = null)
    {
        if (($value = getenv($key)) === false) {
            return self::value($default);
        }
        
        if (array_key_exists(strtolower($value), self::$valuesMatches)) {
            return self::$valuesMatches[strtolower($value)];
        }
        
        if (strlen($value) > 1 && Str::startsEndsWith($value, ['"', '\''])) {
            return substr($value, 1, -1);
        }
        
        return $value;
    }
}
