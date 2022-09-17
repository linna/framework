<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
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
 * Throw it to indicate that the authentication procedure went worng.
 */
class AuthenticationException extends RedirectException
{
}
