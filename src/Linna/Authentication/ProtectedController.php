<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
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
     * @param Authenticate $authenticate
     * @param string       $redirect
     */
    private function protect(Authenticate $authenticate, string $redirect)
    {
        if (($this->authentication = $authenticate->isLogged()) === false) {
            header('Location: '.$redirect);
        }
    }
}
