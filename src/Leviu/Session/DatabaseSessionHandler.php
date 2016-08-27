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
use SessionHandler;
use SessionHandlerInterface;

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
class DatabaseSessionHandler extends SessionHandler implements SessionHandlerInterface
{
    /**
     * @var object Database Connection
     */
    private $db;

    /**
     * Class constructor.
     * 
     * @param string $name Specify sesson name
     */
    public function __construct()
    {

    }

    /**
     * open
     * http://php.net/manual/en/sessionhandler.open.php.
     * 
     * @param string $save_path
     * @param string $session_name
     *
     * @return bool
     */
    public function open($save_path, $session_name)
    {
        $this->db = Database::connect();

        return true;
    }

    /**
     * gc
     * http://php.net/manual/en/sessionhandler.gc.php.
     * 
     * @param string $maxlifetime
     *
     * @return bool
     */
    public function gc($maxlifetime)
    {
        $pdos = $this->db->prepare('DELETE FROM session WHERE last_update < DATE_SUB(NOW(), INTERVAL :maxlifetime SECOND)');

        $pdos->bindParam(':maxlifetime', $maxlifetime, \PDO::PARAM_INT);
        $pdos->execute();

        return true;
    }

    /**
     * read
     * http://php.net/manual/en/sessionhandler.read.php.
     * 
     * @param string $session_id
     *
     * @return mixed :)
     */
    public function read($session_id)
    {
        $pdos = $this->db->prepare('SELECT session_data FROM session WHERE session_id = :session_id');

        $pdos->bindParam(':session_id', $session_id, \PDO::PARAM_STR);
        $pdos->execute();

        return (string) $pdos->fetchColumn();
    }

    /**
     * write
     * http://php.net/manual/en/sessionhandler.write.php.
     * 
     * @param string $session_id
     * @param array  $data
     *
     * @return bool
     */
    public function write($session_id, $data)
    {
        $pdos = $this->db->prepare('INSERT INTO session SET session_id = :session_id, session_data = :session_data ON DUPLICATE KEY UPDATE session_data = :session_data');

        $pdos->bindParam(':session_id', $session_id, \PDO::PARAM_STR);
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
     * @param string $session_id
     *
     * @return bool
     */
    public function destroy($session_id)
    {
        $pdos = $this->db->prepare('DELETE FROM session WHERE session_id = :session_id');
        $pdos->bindParam(':session_id', $session_id, \PDO::PARAM_STR);
        $pdos->execute();

        return true;
    }
}
