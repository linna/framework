<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Shared;

/**
 * Class Options Trait
 * Provide methods for manage options in a class.
 *
 * @property mixed $options Class options property
 */
trait ClassOptionsTrait
{
    /**
     * Set an Option.
     *
     * @param string $key
     * @param type   $value
     *
     * @throws \InvalidArgumentException If provided option name (key) are not valid
     */
    public function setOption(string $key, $value)
    {
        if (!isset($this->options[$key])) {
            throw new \InvalidArgumentException(__CLASS__." class does not support the {$key} option.");
        }

        $this->options[$key] = $value;
    }

    /**
     * Set multiple Options.
     *
     * @param array $options
     * 
     * @throws \InvalidArgumentException If provided option names are not valid
     */
    public function setOptions(array $options)
    {
        $badKeys = array_diff_key($options, $this->options);

        if (sizeof($badKeys) > 0) {

            $keys = implode(', ', array_keys($badKeys));

            throw new \InvalidArgumentException(__CLASS__." class does not support the {$keys} option.");
        }

        $this->options = array_replace_recursive($this->options, $options);
    }
}
