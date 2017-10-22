<?php

/**
 * Determine if a given string starts with a given substring.
 *
 * @param  string $haystack
 * @param  string|array $needles
 * @return bool
 * @link https://github.com/laravel/framework/blob/43eeceda41cfec19fca6765e4e74f01caec20c5c/src/Illuminate/Support/Str.php#L466
 */
function startsWith($haystack, $needles)
{
    foreach ((array)$needles as $needle) {
        if ($needle !== '' && substr($haystack, 0, strlen($needle)) === (string)$needle) {
            return true;
        }
    }
    return false;
}

/**
 * Determine if a given string ends with a given substring.
 *
 * @param  string $haystack
 * @param  string|array $needles
 * @return bool
 * @link https://github.com/laravel/framework/blob/43eeceda41cfec19fca6765e4e74f01caec20c5c/src/Illuminate/Support/Str.php#L118
 */
function endsWith($haystack, $needles)
{
    foreach ((array)$needles as $needle) {
        if (substr($haystack, -strlen($needle)) === (string)$needle) {
            return true;
        }
    }
    return false;
}

/**
 * Return the default value of the given value.
 *
 * @param  mixed $value
 * @return mixed
 */
function value($value)
{
    return $value instanceof Closure ? $value() : $value;
}

/**
 * Gets the value of an environment variable.
 *
 * @param  string $key
 * @param  mixed $default
 * @return mixed
 * @link https://github.com/laravel/framework/blob/43eeceda41cfec19fca6765e4e74f01caec20c5c/src/Illuminate/Support/helpers.php#L595
 */
function env($key, $default = null)
{
    $value = getenv($key);
    if ($value === false) {
        return value($default);
    }
    switch (strtolower($value)) {
        case 'true':
        case '(true)':
            return true;
        case 'false':
        case '(false)':
            return false;
        case 'empty':
        case '(empty)':
            return '';
        case 'null':
        case '(null)':
            return;
    }
    if (strlen($value) > 1 && startsWith($value, '"') && endsWith($value, '"')) {
        return substr($value, 1, -1);
    }
    return $value;
}
