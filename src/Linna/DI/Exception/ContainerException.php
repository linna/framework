<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\DI\Exception;

use Psr\Container\ContainerExceptionInterface;

/**
 * Container Exception.
 */
class ContainerException extends \Exception implements ContainerExceptionInterface
{
}
