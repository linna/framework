<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Linna\FOO;

use Linna\classOptionsTrait;

/**
 * Description of FOOClassOP
 *
 * @author Sebastian
 */
class FOOClassOP
{
    use classOptionsTrait;
    
    private $options = [
        'option1' => true,
        'option2' => true
    ];
    
    public function __construct(array $options)
    {
        $this->options = $this->overrideOptions($this->options, $options);
    }
    
    public function getCurrentOptions() : array
    {
        return $this->options;
    }
}