<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Session;

use Linna\Storage\ExtendedPDO;
use SessionHandlerInterface;

/**
 * Store sessions in Database.
 *
 * Check below link for PHP session Handler
 * http://php.net/manual/en/class.sessionhandler.php
 *
 * Before use create table session on DB.
 *
 *   CREATE TABLE `session` (
 *     `session_id` char(128) NOT NULL,
 *     `session_data` varchar(3096) NOT NULL,
 *     `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 *     `last_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 *     PRIMARY KEY (`session_id`)
 *   ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 */
class MysqlPdoSessionHandler implements SessionHandlerInterface
{
    /**
     * @var ExtendedPDO Database Connection
     */
    protected ExtendedPDO $pdo;

    /**
     * Constructor.
     *
     * @param ExtendedPDO $pdo
     */
    public function __construct(ExtendedPDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Open session storage.
     *
     * http://php.net/manual/en/sessionhandler.open.php.
     *
     * @param string $path
     * @param string $name
     *
     * @return bool
     */
    public function open(string $path, string $name): bool
    {
        unset($path, $name);

        return true;
    }

    /**
     * Delete old sessions from storage.
     *
     * http://php.net/manual/en/sessionhandler.gc.php.
     *
     * @param int $max_lifetime
     *
     * @return int|false
     */
    public function gc(int $max_lifetime): int|false
    {
        $this->pdo->queryWithParam(
            'DELETE FROM session WHERE last_update < DATE_SUB(NOW(), INTERVAL :maxlifetime SECOND)',
            [[':maxlifetime', $max_lifetime, \PDO::PARAM_INT]]
        );

        // need a review to renurn number of records affected by operation
        return (int) $this->pdo->getLastOperationStatus();
    }

    /**
     * Read session data from storage.
     *
     * http://php.net/manual/en/sessionhandler.read.php.
     *
     * @param string $id
     *
     * @return string|false
     */
    public function read(string $id): string|false
    {
        //string casting is a fix for PHP 7
        //when strict type are enable
        return (string) $this->pdo->queryWithParam(
            'SELECT session_data FROM session WHERE session_id = :session_id',
            [[':session_id', $id, \PDO::PARAM_STR]]
        )->fetchColumn();
    }

    /**
     * Write session data to storage.
     *
     * http://php.net/manual/en/sessionhandler.write.php.
     *
     * @param string $id
     * @param string $data
     *
     * @return bool
     */
    public function write(string $id, string $data): bool
    {
        $this->pdo->queryWithParam(
            'INSERT INTO session SET session_id = :session_id, session_data = :session_data ON DUPLICATE KEY UPDATE session_data = :session_data',
            [
                [':session_id', $id, \PDO::PARAM_STR],
                [':session_data', $data, \PDO::PARAM_STR]
            ]
        );

        return $this->pdo->getLastOperationStatus();
    }

    /**
     * Close session.
     *
     * http://php.net/manual/en/sessionhandler.close.php.
     *
     * @return bool
     */
    public function close(): bool
    {
        return true;
    }

    /**
     * Destroy session data.
     *
     * http://php.net/manual/en/sessionhandler.destroy.php.
     *
     * @param string $id
     *
     * @return bool
     */
    public function destroy(string $id): bool
    {
        $this->pdo->queryWithParam(
            'DELETE FROM session WHERE session_id = :session_id',
            [[':session_id', $id, \PDO::PARAM_STR]]
        );

        return $this->pdo->getLastOperationStatus();
    }
}
