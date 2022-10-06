<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Session;

/**
 * Abstract class for queries used in PdoSessionHandler.
 *
 * <p>All constants declared in this abstract class must be overridden from a subclass.<p>
 */
abstract class PdoAbstractQuery
{
    /** @var s */
    public const QUERY_READ = '';
    public const QUERY_WRITE = '';
    public const QUERY_DESTROY = '';
    public const QUERY_GC = '';
}
