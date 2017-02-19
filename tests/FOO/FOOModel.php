<?php

/**
 * Linna App.
 *
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\FOO;

use Linna\Mvc\Model;

class FOOModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addToData(string $when)
    {
        if (!isset($this->getUpdate['data']))
        {
            $this->getUpdate = ['data' => 100];
            return;
        }
        
        $this->getUpdate['data'] += 3;
    }
    
    public function modifyDataTimed()
    {
        $this->getUpdate['data'] += 20;
    }
    
    public function modifyData()
    {
        $this->getUpdate = ['data' => 1234];
    }

    public function modifyDataFromParam($param)
    {
        $this->getUpdate = ['data' => $param];
    }
}
