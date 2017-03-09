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
 * Provide methods for set options.
 *
 * @property mixed $options Class options property
 */
trait ClassOptionsTrait
{
    /**
     * Set an Option.
     * 
     * @param string $key   Key
     * @param type   $value Value
     * @throws \InvalidArgumentException
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
     * @param array $options Options
     */
    public function setOptions(array $options)
    {
        $this->options = array_replace_recursive($this->options, $options);
    }
}
