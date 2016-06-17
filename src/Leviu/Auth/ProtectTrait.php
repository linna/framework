<?php

/**
 * Leviu.
 *
 * This work would be a little PHP framework, a learn exercice. 
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2015, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 * @version 0.1.0
 */
namespace Leviu\Auth;

/**
 * Protect trait
 * - Methods for help to protect a controller under login.
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 */
trait ProtectTrait
{
    /**
     * @var object Login instance
     */
    protected $login;

    /**
     * @var bool Login status 
     */
    protected $isLogged;

    /**
     * protectController.
     * 
     * @param \Leviu\Auth\Login $loginIstance
     */
    protected function protectController(Login $loginIstance)
    {
        if ($loginIstance->isLogged === false) {
            header('location: '.URL.'unauthorized');
            die();
        }

        $this->login = $loginIstance;
        $this->isLogged = $loginIstance->isLogged;
    }
    /**
     * protectMethod.
     * 
     * @param \Leviu\Auth\Login $loginIstance
     */
    protected function protectMethod(Login $loginIstance)
    {
        $this->login = $loginIstance;
        $this->isLogged = $loginIstance->isLogged;
    }
}
