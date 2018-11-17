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

use Linna\Authentication\Exception\AuthenticationException;

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
     * Allow access to controller class or methods only if logged.
     * Return a status code, useful with AJAX requests.
     *
     * @param Authentication $authentication
     * @param int            $httpResponseCode
     */
    private function protect(Authentication $authentication, int $httpResponseCode = 403): void
    {
        if (($this->authentication = $authentication->isLogged()) === false) {
            http_response_code($httpResponseCode);
            throw new AuthenticationException('');
        }
    }

    /**
     * Allow access to controller class or methods only if logged
     * and do a redirection.
     *
     * @param Authentication $authentication
     * @param string         $location
     */
    private function protectWithRedirect(Authentication $authentication, string $location): void
    {
        if (($this->authentication = $authentication->isLogged()) === false) {
            header('Location: '.$location);
            throw new AuthenticationException('');
        }
    }
}
