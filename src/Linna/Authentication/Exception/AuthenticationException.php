<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Authentication\Exception;

use Linna\Router\Exception\RedirectException;

/**
 * Authentication Exception.
 */
class AuthenticationException extends RedirectException
{
}
