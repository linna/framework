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

/**
 * Description of FOOClassA
 *
 * @author Sebastian
 */
class FOOClassA
{
    private $hello;
    
    public function __construct()
    {
       $this->hello = 'hello';
    }
    
    public function getHello()
    {
        return $this->hello;
    }
}
