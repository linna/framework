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

use Memcached;
use SessionHandlerInterface;

/**
 * Store sessions in Memcached.
 *
 * Check below link for PHP session Handler
 * http://php.net/manual/en/class.sessionhandler.php
 */
class MemcachedSessionHandler implements SessionHandlerInterface
{
    /**
     * @var Memcached Memcached instance
     */
    private $memcached;

    /**
     * @var int Expire time in seconds for stored sessions
     */
    private $expire;

    /**
     * Constructor.
     *
     * @param Memcached $memcached Memcached resource
     * @param int       $expire    Expire time in seconds for stored sessions
     */
    public function __construct(Memcached $memcached, int $expire)
    {
        $this->memcached = $memcached;
        $this->expire = $expire;
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
        unset($maxlifetime);

        //this method is no needed because all object stored expire without external operation
        return true;
    }

    /**
     * Read sessio data from storage.
     *
     * http://php.net/manual/en/sessionhandler.read.php.
     *
     * @param string $session_id
     *
     * @return string
     */
    public function read($session_id)
    {
        //fix for php7
        return (string) $this->memcached->get($session_id);
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
        return $this->memcached->set($session_id, $session_data, $this->expire);
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
        if ($this->memcached->delete($session_id)) {
            return true;
        }

        if ($this->memcached->getResultCode() === 16) {
            return true;
        }

        return false;
    }
}
