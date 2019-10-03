<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Authorization\Exception;

use Linna\Router\Exception\RedirectException;

/**
 * Authorization Exception.
 */
class AuthorizationException extends RedirectException
{
}
