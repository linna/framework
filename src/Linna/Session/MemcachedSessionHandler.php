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
     * Class Constructor.
     *
     * @param Memcached $memcached Memcached resource
     * @param int       $expire    Expire time in seconds for stored sessions
     */
    public function __construct(private Memcached $memcached, private int $expire = 0)
    {
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
        unset($max_lifetime);

        //this method is no needed because all object stored expire without external operation
        return 0;
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
        //fix for php7
        return (string) $this->memcached->get($id);
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
        return $this->memcached->set($id, $data, $this->expire);
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
        if ($this->memcached->delete($id)) {
            return true;
        }

        if ($this->memcached->getResultCode() === 16) {
            return true;
        }

        return false;
    }
}
