<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Authorization\Exception;

use Linna\Router\Exception\RedirectException;

/**
 * Authorization Exception.
 *
 * <p>Throw it to indicate that the authorization procedure went worng.</p>
 */
class AuthorizationException extends RedirectException
{
}
