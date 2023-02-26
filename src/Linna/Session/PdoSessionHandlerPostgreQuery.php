<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Session;

/**
 * Postgre queries to store sessions in database.
 *
 * <p>All queries are are made to be used with <code>PDOStatement::bindParam</code> method.</p>
 * <p>All queries are for the table below:</p>
 * <pre>
 * CREATE TABLE session (
 *   session_id char(255) NOT NULL,
 *   session_data varchar(4096) NOT NULL,
 *   last_update timestamp NOT NULL,
 *   PRIMARY KEY (session_id)
 * );
 * </pre>
 */
class PdoSessionHandlerPostgreQuery implements PdoSessionHandlerQueryInterface
{
    /** @var string The query used to read session data from data base. <p>has <code>:id</code> as parameter for <code>PDOStatement::bindParam</code>.</p>*/
    final public const QUERY_READ = 'SELECT session_data FROM public.session WHERE session_id = :id';

    /** @var string The query used to write session data from data base. <p>has <code>:id</code> and <code>:data</code> as parameters for <code>PDOStatement::bindParam</code>.</p>*/
    final public const QUERY_WRITE = 'INSERT INTO public.session(session_id, session_data) VALUES (:id, :data) ON CONFLICT (session_id) DO UPDATE SET session_data = :data, last_update = now()';

    /** @var string The query used to destroy session data from data base. <p>has <code>:id</code> as parameter for <code>PDOStatement::bindParam</code>.</p>*/
    final public const QUERY_DESTROY = 'DELETE FROM public.session WHERE session_id = :id';

    /** @var string The query used to delete expired session data from data base. <p>has <code>:max_lifetime</code> as parameter for <code>PDOStatement::bindParam</code>.</p>*/
    final public const QUERY_GC = 'DELETE FROM public.session WHERE last_update < :max_lifetime';
}
