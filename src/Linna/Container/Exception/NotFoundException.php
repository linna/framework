<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 * @ignore
 */
declare(strict_types=1);

namespace Linna\Container\Exception;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Not Found Exception.
 */
class NotFoundException extends Exception implements NotFoundExceptionInterface
{
}
