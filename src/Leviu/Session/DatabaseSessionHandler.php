<?php

/**
 * Leviu
 *
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Leviu\Session;

use Leviu\Database\Database;
use \SessionHandlerInterface;

/**
 * Database Session Handler
 * - Class for store sessions in Database.
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
class DatabaseSessionHandler implements SessionHandlerInterface
{
    /**
     * @var object Database Connection
     */
    private $db;

    /**
     * Class constructor.
     * 
     */
    public function __construct()
    {

    }

    /**
     * open
     * http://php.net/manual/en/sessionhandler.open.php.
     * 
     * @param string $savePath
     * @param string $sessionName
     *
     * @return bool
     */
    public function open($savePath, $sessionName)
    {
        $this->db = Database::connect();

        return true;
    }

    /**
     * gc
     * http://php.net/manual/en/sessionhandler.gc.php.
     * 
     * @param string $maxLifetime
     *
     * @return bool
     */
    public function gc($maxLifetime)
    {
        $pdos = $this->db->prepare('DELETE FROM session WHERE last_update < DATE_SUB(NOW(), INTERVAL :maxlifetime SECOND)');

        $pdos->bindParam(':maxlifetime', $maxLifetime, \PDO::PARAM_INT);
        $pdos->execute();

        return true;
    }

    /**
     * read
     * http://php.net/manual/en/sessionhandler.read.php.
     * 
     * @param string $sessionId
     *
<<<<<<< HEAD
     * @return string
=======
     * @return string :)
>>>>>>> origin/master
     */
    public function read($sessionId)
    {
        $pdos = $this->db->prepare('SELECT session_data FROM session WHERE session_id = :session_id');

        $pdos->bindParam(':session_id', $sessionId, \PDO::PARAM_STR);
        $pdos->execute();

        //fix for php7
        return (string) $pdos->fetchColumn();
    }

    /**
     * write
     * http://php.net/manual/en/sessionhandler.write.php.
     * 
     * @param string $sessionId
     * @param array  $data
     *
     * @return bool
     */
    public function write($sessionId, $data)
    {
        $pdos = $this->db->prepare('INSERT INTO session SET session_id = :session_id, session_data = :session_data ON DUPLICATE KEY UPDATE session_data = :session_data');

        $pdos->bindParam(':session_id', $sessionId, \PDO::PARAM_STR);
        $pdos->bindParam(':session_data', $data, \PDO::PARAM_STR);
        $pdos->execute();

        return true;
    }

    /**
     * close
     * http://php.net/manual/en/sessionhandler.close.php.
     * 
     * @return bool
     */
    public function close()
    {
        return true;
    }

    /**
     * destroy
     * http://php.net/manual/en/sessionhandler.destroy.php.
     * 
     * @param string $sessionId
     *
     * @return bool
     */
    public function destroy($sessionId)
    {
        $pdos = $this->db->prepare('DELETE FROM session WHERE session_id = :session_id');
        $pdos->bindParam(':session_id', $sessionId, \PDO::PARAM_STR);
        $pdos->execute();

        return true;
    }
}
