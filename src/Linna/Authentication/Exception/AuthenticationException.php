<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Authentication\Exception;

use Linna\Router\Exception\RedirectException;

/**
 * Authentication Exception.
 *
 * <p>Throw it to indicate that the authentication procedure went wrong.</p>
 */
class AuthenticationException extends RedirectException
{
}
