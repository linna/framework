<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Authentication;

use Linna\Authentication\Authentication;
use Linna\Authentication\Exception\AuthenticationException;

/**
 * Help protect a controller with login.
 *
 * <p>This trait add to a controller the ability to interrupt its own execution, in case authentication is required.</p>
 *
 * <p>This trait contains only private mothods.</p>
 */
trait ProtectedControllerTrait
{
    /** @var bool Contain login status. */
    private bool $authentication = false;

    /**
     * Allow access to controller class or methods only if logged.
     *
     * <p>Return a status code, useful with AJAX requests.</p>
     *
     * @param Authentication $authentication <code>Authentication</code> class instance.
     * @param string         $route          Valid route path for <code>AuthenticationException</code>.
     *
     * @return void
     *
     * @throws AuthenticationException if user not authenticathed.
     */
    private function protect(Authentication $authentication, string $route): void
    {
        if (($this->authentication = $authentication->isLogged()) === false) {
            throw new AuthenticationException($route);
        }
    }

    /**
     * Allow access to controller class or methods only if logged and do an HTTP redirection if not.
     *
     * @param Authentication $authentication <code>Authentication</code> class instance.
     * @param string         $location       Valid url for Location header.
     * @param string         $route          Valid route path for <code>AuthenticationException</code>.
     *
     * @return void
     *
     * @throws AuthenticationException if user not authenticathed.
     */
    private function protectWithRedirect(Authentication $authentication, string $location, string $route): void
    {
        if (($this->authentication = $authentication->isLogged()) === false) {
            \header('Location: '.$location);
            throw new AuthenticationException($route);
        }
    }
}
