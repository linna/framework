<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\DataMapper\Exception;

/**
 * Null Domain Object Exception.
 *
 * <p>Throw it to when there is an attempt to set or to get the id of a <code>NullDomainObject</code>.</p>
 */
class NullDomainObjectException extends Exception
{
}
