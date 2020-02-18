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
 * CREATE TABLE session (
 *   session_id char(255) NOT NULL,
 *   session_data varchar(4096) NOT NULL,
 *   last_update timestamp NOT NULL,
 *   PRIMARY KEY (session_id)
 * );
 */
class PgsqlPdoSessionHandler implements SessionHandlerInterface
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
     * @param string $save_path
     * @param string $session_name
     *
     * @return bool
     */
    public function open($save_path, $session_name)
    {
        unset($save_path, $session_name);

        return true;
    }

    /**
     * Delete old sessions from storage.
     *
     * http://php.net/manual/en/sessionhandler.gc.php.
     *
     * @param int $maxlifetime
     *
     * @return bool
     */
    public function gc($maxlifetime)
    {
        $timestamp = \date(DATE_ATOM, \time() - $maxlifetime);

        $this->pdo->queryWithParam(
            'DELETE FROM public.session WHERE last_update < :maxlifetime',
            [[':maxlifetime', $timestamp, \PDO::PARAM_STR]]
        );

        return $this->pdo->getLastOperationStatus();
    }

    /**
     * Read session data from storage.
     *
     * http://php.net/manual/en/sessionhandler.read.php.
     *
     * @param string $session_id
     *
     * @return string
     */
    public function read($session_id)
    {
        //string casting is a fix for PHP 7
        //when strict type are enable
        return (string) $this->pdo->queryWithParam(
            'SELECT session_data FROM public.session WHERE session_id = :session_id',
            [[':session_id', $session_id, \PDO::PARAM_STR]]
        )->fetchColumn();
    }

    /**
     * Write session data to storage.
     *
     * http://php.net/manual/en/sessionhandler.write.php.
     *
     * @param string $session_id
     * @param string $session_data
     *
     * @return bool
     */
    public function write($session_id, $session_data)
    {
        $this->pdo->queryWithParam(
            'INSERT INTO public.session(session_id, session_data) VALUES (:session_id, :session_data) ON CONFLICT (session_id) DO UPDATE SET session_data = :session_data, last_update = now()',
            [
                [':session_id', $session_id, \PDO::PARAM_STR],
                [':session_data', $session_data, \PDO::PARAM_STR]
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
     * @param string $session_id
     *
     * @return bool
     */
    public function destroy($session_id)
    {
        $this->pdo->queryWithParam(
            'DELETE FROM public.session WHERE session_id = :session_id',
            [[':session_id', $session_id, \PDO::PARAM_STR]]
        );

        return $this->pdo->getLastOperationStatus();
    }
}
