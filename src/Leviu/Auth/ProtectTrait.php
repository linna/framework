<?php

/**
 * Leviu
 *
 * This work would be a little PHP framework, a learn exercice. 
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2015, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 * @version 0.1.0
 */

namespace Leviu\Auth;

trait ProtectTrait
{
    protected $login;
    protected $isLogged;
    
    protected function protectController(Login $loginIstance)
    {
        if ($loginIstance->isLogged === false) {
            header("location: " . URL."unauthorized");
            die();
        }
        
        $this->login = $loginIstance;
        $this->isLogged = $loginIstance->isLogged;
    }
    
    protected function protectMethod(Login $loginIstance)
    {
        $this->login = $loginIstance;
        $this->isLogged = $loginIstance->isLogged;
    }
}
