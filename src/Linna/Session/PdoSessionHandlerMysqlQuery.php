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
 * 
 * <p>All queries are are made to be used with <code>PDOStatement::bindParam</code> method.</p>
 * <p>All queries are for the table below:</p>
 * <pre>
 * CREATE TABLE `session` (
 *   `session_id` char(128) NOT NULL,
 *   `session_data` varchar(3096) NOT NULL,
 *   `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 *   `last_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 *   PRIMARY KEY (`session_id`)
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 * </pre>
 */
class PdoSessionHandlerMysqlQuery implements PdoSessionHandlerQueryInterface
{
    /** @var string The query used to read session data from data base. <p>has <code>:id</code> as parameter for <code>PDOStatement::bindParam</code></p>*/
    final public const QUERY_READ = 'SELECT session_data FROM session WHERE session_id = :id';
    
    /** @var string The query used to write session data from data base. <p>has <code>:id</code> and <code>:data</code> as parameters for <code>PDOStatement::bindParam</code></p>*/
    final public const QUERY_WRITE = 'INSERT INTO session SET session_id = :id, session_data = :data ON DUPLICATE KEY UPDATE session_data = :data';
    
    /** @var string The query used to destroy session data from data base. <p>has <code>:id</code> as parameter for <code>PDOStatement::bindParam</code></p>*/
    final public const QUERY_DESTROY = 'DELETE FROM session WHERE session_id = :id';
    
    /** @var string The query used to delete expired session data from data base. <p>has <code>:max_lifetime</code> as parameter for <code>PDOStatement::bindParam</code></p>*/
    final public const QUERY_GC = 'DELETE FROM session WHERE last_update < :max_lifetime';
}
