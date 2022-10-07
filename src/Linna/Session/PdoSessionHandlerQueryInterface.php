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
 * Interface for queries used in PdoSessionHandler.
 *
 * <p>All constants declared in this interface must be overridden into the class that implements it.<p>
 */
interface PdoSessionHandlerQueryInterface
{
    /** @var string The query used to read session data from data base. <p>Must contains <code>:id</code> as parameter for <code>PDOStatement::bindParam</code>.</p>*/
    public const QUERY_READ = '';

    /** @var string The query used to write session data from data base. <p>Must contains <code>:id</code> and <code>:data</code> as parameters for <code>PDOStatement::bindParam</code>.</p>*/
    public const QUERY_WRITE = '';

    /** @var string The query used to destroy session data from data base. <p>Must contains <code>:id</code> as parameter for <code>PDOStatement::bindParam</code>.</p>*/
    public const QUERY_DESTROY = '';

    /** @var string The query used to delete expired session data from data base. <p>Must contains <code>:max_lifetime</code> as parameter for <code>PDOStatement::bindParam</code>.</p>*/
    public const QUERY_GC = '';
}
