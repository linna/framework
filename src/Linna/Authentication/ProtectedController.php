<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Authentication;

/**
 * Help protect a controller with login.
 */
trait ProtectedController
{
    /**
     * @var bool Contain login status
     */
    private $authentication = false;

    /**
     * Allow access to controller only if logged.
     *
     * @param Authentication $authentication
     * @param string         $redirect
     */
    private function protect(Authentication $authentication, string $redirect): void
    {
        if (($this->authentication = $authentication->isLogged()) === false) {
            header('Location: '.$redirect);
        }
    }
}
