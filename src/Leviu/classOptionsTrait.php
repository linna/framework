<?php

/**
 * Leviu
 *
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Leviu;

/**
 * Methods for help to protect a controller with login.
 * 
 */
trait classOptionsTrait
{
    /**
     * Override default options
     * 
     * @param array $options
     */
    protected function overrideOptions($options)
    {
        foreach ($options as $key => $value) {
            if (isset($this->options[$key])) {
                $this->options[$key] = $value;
            }
        }
    }
}
