<?php

/**
 * Linna App
 *
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Linna\FOO;

use Linna\Mvc\TemplateInterface;

class FOOTemplate implements TemplateInterface
{
    public $data = null;
    
    
    public function __construct()
    {
        $this->data = (object) null;
    }
    
    public function output()
    {
        echo json_encode($this->data);
    }
}
