<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

declare(strict_types=1);

namespace Linna;

/**
 * Methods for help to protect a controller with login.
 *
 */
trait classOptionsTrait
{
    /**
     * Override default options
     *
     * @param array $classOptions Options inside class
     * @param array $options Passed Options
     *
     * @return array Updated options
     */
    protected function overrideOptions(array $classOptions, array $options)
    {
        foreach ($options as $key => $value) {
            if (isset($classOptions[$key])) {
                $classOptions[$key] = $value;
            }
        }
        
        return (array) $classOptions;
    }
}
