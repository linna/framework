<?php

/**
 * Linna App
 *
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Linna\FOO;

use Linna\Mvc\Model;

class FOOModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function modifyData()
    {
        $this->getUpdate = ['data' => 'modified data'];
    }
}
