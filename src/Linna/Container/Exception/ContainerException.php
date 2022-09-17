<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Container\Exception;

use Exception;
use Psr\Container\ContainerExceptionInterface;

/**
 * Container Exception.
 */
class ContainerException extends Exception implements ContainerExceptionInterface
{
}
