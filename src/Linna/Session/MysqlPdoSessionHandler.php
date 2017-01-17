<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

declare(strict_types=1);

namespace Linna\Session;

use SessionHandlerInterface;
use Linna\Storage\MysqlPdoAdapter;

/**
 * Store sessions in Database.
 *
 * Check below link for PHP session Handler
 * http://php.net/manual/en/class.sessionhandler.php
 *
 * Before use create table session on DB, I prefered memory engine for speed
 *
 * CREATE TABLE `session` (
 *  `session_id` char(128) NOT NULL,
 *  `session_data` varchar(8191) NOT NULL,
 *  `last_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 *  PRIMARY KEY (`session_id`)
 * ) ENGINE=MEMORY DEFAULT CHARSET=utf8;
 */
class MysqlPDOSessionHandler implements SessionHandlerInterface
{
    /**
     * @var object $dBase Database Connection
     */
    private $dBase;

    /**
     * Constructor
     *
     * @param MysqlPdoAdapter $storage MysqlPdoResource
     */
    public function __construct(MysqlPdoAdapter $storage)
    {
        $this->dBase = $storage->getResource();
    }

    /**
     * Open session storage
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
     * Delete old sessions from storage
     *
     * http://php.net/manual/en/sessionhandler.gc.php.
     *
     * @param string $maxLifetime
     *
     * @return bool
     */
    public function gc($maxLifetime)
    {
        $pdos = $this->dBase->prepare('DELETE FROM session WHERE last_update < DATE_SUB(NOW(), INTERVAL :maxlifetime SECOND)');

        $pdos->bindParam(':maxlifetime', $maxLifetime, \PDO::PARAM_INT);
        $pdos->execute();

        return true;
    }

    /**
     * Read sessio data from storage
     *
     * http://php.net/manual/en/sessionhandler.read.php.
     *
     * @param string $sessionId
     *
     * @return string
     */
    public function read($sessionId)
    {
        $pdos = $this->dBase->prepare('SELECT session_data FROM session WHERE session_id = :session_id');

        $pdos->bindParam(':session_id', $sessionId, \PDO::PARAM_STR);
        $pdos->execute();
        
        //fix for php7
        return (string) $pdos->fetchColumn();
    }

    /**
     * Write session data to storage
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
        $pdos = $this->dBase->prepare('INSERT INTO session SET session_id = :session_id, session_data = :session_data ON DUPLICATE KEY UPDATE session_data = :session_data');

        $pdos->bindParam(':session_id', $sessionId, \PDO::PARAM_STR);
        $pdos->bindParam(':session_data', $data, \PDO::PARAM_STR);
        $pdos->execute();

        return true;
    }

    /**
     * Close session
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
     * Destroy session data
     *
     * http://php.net/manual/en/sessionhandler.destroy.php.
     *
     * @param string $sessionId
     *
     * @return bool
     */
    public function destroy($sessionId)
    {
        $pdos = $this->dBase->prepare('DELETE FROM session WHERE session_id = :session_id');
        $pdos->bindParam(':session_id', $sessionId, \PDO::PARAM_STR);
        $pdos->execute();

        return true;
    }
}
