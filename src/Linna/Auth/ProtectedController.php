<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Linna\Auth;

/**
 * Help protect a controller with login.
 *
 */
trait ProtectedController
{
    /**
     * Allow access to controller only if logged
     *
     * @param \Linna\Auth\Login $loginIstance
     * @param string $redirect
     */
    protected function protect(Login $loginIstance, $redirect)
    {
        if ($loginIstance->logged === false) {
            header('location: '.$redirect);
        }
    }
}
