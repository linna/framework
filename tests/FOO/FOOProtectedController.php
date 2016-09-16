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

use Linna\Mvc\Controller;
use Linna\Auth\ProtectedController;
use Linna\Auth\Login;
use Linna\FOO\FOOModel;

class FOOProtectedController extends Controller
{
    use ProtectedController;
    
    public $test;
    
    public function __construct(FOOModel $model, Login $login)
    {
        parent::__construct($model);
        
        $this->test = false;
        
        $this->protect($login, 'http://localhost');
        
        $this->test = true;  
    }
}
