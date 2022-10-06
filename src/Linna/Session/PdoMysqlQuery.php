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
 * Mysql queries to store sessions in database.
 */
class PdoMysqlQuery extends PdoAbstractQuery
{
    /** @var string Description */
    final public const QUERY_READ = 'SELECT session_data FROM session WHERE session_id = :id';
    final public const QUERY_WRITE = 'INSERT INTO session SET session_id = :id, session_data = :data ON DUPLICATE KEY UPDATE session_data = :data';
    final public const QUERY_DESTROY = 'DELETE FROM session WHERE session_id = :id';
    final public const QUERY_GC = 'DELETE FROM session WHERE last_update < :max_lifetime';
}
