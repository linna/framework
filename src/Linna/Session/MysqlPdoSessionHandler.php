<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
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
 * CREATE TABLE `session` (
 *  `session_id` char(128) NOT NULL,
 *  `session_data` varchar(3096) NOT NULL,
 *  `last_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 *  PRIMARY KEY (`session_id`)
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 */
class MysqlPdoSessionHandler implements SessionHandlerInterface
{
    /**
     * @var ExtendedPDO Database Connection
     */
    private $pdo;

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
     * @param string $savePath
     * @param string $sessionName
     *
     * @return bool
     */
    public function open($savePath, $sessionName)
    {
        unset($savePath, $sessionName);
        
        return true;
    }
        
    /**
     * Delete old sessions from storage.
     *
     * http://php.net/manual/en/sessionhandler.gc.php.
     *
     * @param int $maxLifetime
     *
     * @return bool
     */
    public function gc($maxLifetime)
    {
        $this->pdo->queryWithParam(
            'DELETE FROM session WHERE last_update < DATE_SUB(NOW(), INTERVAL :maxlifetime SECOND)',
            [[':maxlifetime', $maxLifetime, \PDO::PARAM_INT]]
        );

        return $this->pdo->getLastOperationStatus();
    }

    /**
     * Read session data from storage.
     *
     * http://php.net/manual/en/sessionhandler.read.php.
     *
     * @param string $sessionId
     *
     * @return string
     */
    public function read($sessionId)
    {
        //string casting is a fix for PHP 7
        //when strict type are enable
        return (string) $this->pdo->queryWithParam(
            'SELECT session_data FROM session WHERE session_id = :session_id',
            [[':session_id', $sessionId, \PDO::PARAM_STR]]
        )->fetchColumn();
    }

    /**
     * Write session data to storage.
     *
     * http://php.net/manual/en/sessionhandler.write.php.
     *
     * @param string $sessionId
     * @param array  $data
     *
     * @return bool
     */
    public function write($sessionId, $data)
    {
        $this->pdo->queryWithParam(
            'INSERT INTO session SET session_id = :session_id, session_data = :session_data ON DUPLICATE KEY UPDATE session_data = :session_data',
            [
                [':session_id', $sessionId, \PDO::PARAM_STR],
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
    public function close()
    {
        return true;
    }

    /**
     * Destroy session data.
     *
     * http://php.net/manual/en/sessionhandler.destroy.php.
     *
     * @param string $sessionId
     *
     * @return bool
     */
    public function destroy($sessionId)
    {
        $this->pdo->queryWithParam(
            'DELETE FROM session WHERE session_id = :session_id',
            [[':session_id', $sessionId, \PDO::PARAM_STR]]
        );

        return $this->pdo->getLastOperationStatus();
    }
}
