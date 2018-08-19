<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Shared;

use InvalidArgumentException;

/**
 * Provide methods for manage options in a class.
 *
 * @property mixed $options Class options property
 */
trait ClassOptionsTrait
{
    /**
     * Set an option.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @throws \InvalidArgumentException If provided option name (key) are not valid
     */
    public function setOption(string $key, $value): void
    {
        if (isset($this->options[$key])) {
            $this->options[$key] = $value;
            return;
        }

        throw new InvalidArgumentException(__CLASS__." class does not support the {$key} option");
    }

    /**
     * Set one or more options.
     *
     * @param array $options
     *
     * @throws \InvalidArgumentException If provided option names are not valid
     */
    public function setOptions(array $options): void
    {
        $badKeys = array_diff_key($options, $this->options);

        if (count($badKeys) > 0) {
            $keys = implode(', ', array_keys($badKeys));

            throw new InvalidArgumentException(__CLASS__." class does not support the {$keys} option");
        }

        $this->options = array_replace_recursive($this->options, $options);
    }
}
