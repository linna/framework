<?php

/**
 * Leviu
 *
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Leviu\Auth;

/**
 * Methods for help to protect a controller with login.
 * 
 */
trait ProtectTrait
{
    /**
     * @var object $login Login instance
     */
    protected $login;

    /**
     * @var bool $isLogged Login status 
     */
    protected $isLogged;

    /**
     * Allow access to controller only if logged
     * 
     * @param \Leviu\Auth\Login $loginIstance
     * @param string $redirect
     */
    protected function protectController(Login $loginIstance, $redirect)
    {
        if ($loginIstance->isLogged === false) {
            header('location: '.$redirect);
        }

        $this->login = $loginIstance;
        $this->isLogged = $loginIstance->isLogged;
    }
    /**
     * Allow access to controller method only if logged
     * 
     * @param \Leviu\Auth\Login $loginIstance
     */
    protected function protectMethod(Login $loginIstance)
    {
        $this->login = $loginIstance;
        $this->isLogged = $loginIstance->isLogged;
    }
}
