<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
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
 * <b>Before use this class, it is mandatory to enable memcached extension.</p>
 *
 * @link http://php.net/manual/en/class.sessionhandler.php
 */
class MemcachedSessionHandler implements SessionHandlerInterface
{
    /**
     * Class Constructor.
     *
     * @param Memcached $memcached The memcached resource.
     * @param int       $expire    Expire time in seconds for stored sessions.
     */
    public function __construct(
        /** @var Memcached The memcached resource. */
        private Memcached $memcached,
        /** @var int Expire time in seconds for stored sessions. */
        private int $expire = 0
    ) {
    }

    /**
     * Initialize session.
     *
     * <p>Re-initialize existing session, or creates a new one. Called when a session starts or when
     * <code>session_start()</code> is invoked.</p>
     *
     * @param string $path The path where to store/retrieve the session.
     * @param string $name The session name.
     *
     * @return bool The return value (usually <b><code>true</code></b> on success, <b><code>false</code></b> on
     *              failure). Note this value is returned internally to PHP for processing.
     *
     * @link https://php.net/manual/en/sessionhandlerinterface.open.php
     * @see session_name()
     * @since PHP 5 >= 5.4.0, PHP 7, PHP 8
     */
    public function open(string $path, string $name): bool
    {
        unset($path, $name);

        return true;
    }

    /**
     * Cleanup old sessions.
     *
     * <p>Cleans up expired sessions. Called by <code>session_start()</code>, based on session.gc_divisor,
     * session.gc_probability and session.gc_maxlifetime settings.</p>
     * <p>In this session handler this method has not implemented beacause memcached has a built in mechanism to remove
     * expired elements</p>
     *
     * @param int $max_lifetime Sessions that have not updated for the last <code>max_lifetime</code> seconds will be
     *                          removed.
     *
     * @return int|false Returns the number of deleted sessions on success, or <b><code>false</code></b> on failure.
     *                   Note this value is returned internally to PHP for processing.
     *
     * @link https://php.net/manual/en/sessionhandlerinterface.gc.php
     * @since PHP 5 >= 5.4.0, PHP 7, PHP 8
     */
    public function gc(int $max_lifetime): int|false
    {
        unset($max_lifetime);
        return 0;
    }

    /**
     * Read session data.
     *
     * <p>Reads the session data from the session storage, and returns the results. Called right after the session
     * starts or when <code>session_start()</code> is called. Please note that before this method is called
     * <code>SessionHandlerInterface::open()</code> is invoked.</p><p>This method is called by PHP itself when the
     * session is started. This method should retrieve the session data from storage by the session ID provided.
     * The string returned by this method must be in the same serialized format as when originally passed to the
     * <code>SessionHandlerInterface::write()</code> If the record was not found, return <b><code>false</code></b>.</p>
     * <p>The data returned by this method will be decoded internally by PHP using the unserialization method specified
     * in session.serialize_handler. The resulting data will be used to populate the $_SESSION superglobal.</p><p>Note
     * that the serialization scheme is not the same as <code>unserialize()</code> and can be accessed by
     * <code>session_decode()</code>.</p>
     *
     * @param string $id The session id.
     *
     * @return string|false Returns an encoded string of the read data. If nothing was read, it must return
     *                      <b><code>false</code></b>. Note this value is returned internally to PHP for processing.
     *
     * @link https://php.net/manual/en/sessionhandlerinterface.read.php
     * @since PHP 5 >= 5.4.0, PHP 7, PHP 8
     */
    public function read(string $id): string|false
    {
        return (string) $this->memcached->get($id);
    }

    /**
     * Write session data.
     *
     * <p>Writes the session data to the session storage. Called by <code>session_write_close()</code>, when
     * <code>session_register_shutdown()</code> fails, or during a normal shutdown. Note:
     * <code>SessionHandlerInterface::close()</code> is called immediately after this function.</p><p>PHP will call
     * this method when the session is ready to be saved and closed. It encodes the session data from the $_SESSION
     * superglobal to a serialized string and passes this along with the session ID to this method for storage. The
     * serialization method used is specified in the session.serialize_handler setting.</p><p>Note this method is
     * normally called by PHP after the output buffers have been closed unless explicitly called by
     * <code>session_write_close()</code></p>
     *
     * @param string $id   The session id.
     * @param string $data The encoded session data. This data is the result of the PHP internally encoding the
     *                     $_SESSION superglobal to a serialized string and passing it as this parameter. Please note
     *                     sessions use an alternative serialization method.
     *
     * @return bool The return value (usually <b><code>true</code></b> on success, <b><code>false</code></b> on
     *              failure). Note this value is returned internally to PHP for processing.
     *
     * @link https://php.net/manual/en/sessionhandlerinterface.write.php
     * @since PHP 5 >= 5.4.0, PHP 7, PHP 8
     */
    public function write(string $id, string $data): bool
    {
        return $this->memcached->set($id, $data, $this->expire);
    }

    /**
     * Close the session.
     *
     * <p>Closes the current session. This function is automatically executed when closing the session, or explicitly via <code>session_write_close()</code>.</p>
     *
     * @return bool The return value (usually <b><code>true</code></b> on success, <b><code>false</code></b> on failure). Note this value is returned internally to PHP for processing.
     *
     * @link https://php.net/manual/en/sessionhandlerinterface.close.php
     * @since PHP 5 >= 5.4.0, PHP 7, PHP 8
     */
    public function close(): bool
    {
        return true;
    }

    /**
     * Destroy a session.
     *
     * <p>Destroys a session. Called by <code>session_regenerate_id()</code> (with $destroy = <b><code>true</code></b>), <code>session_destroy()</code> and when <code>session_decode()</code> fails.</p>
     *
     * @param string $id The session ID being destroyed.
     *
     * @return bool The return value (usually <b><code>true</code></b> on success, <b><code>false</code></b> on failure). Note this value is returned internally to PHP for processing.
     *
     * @link https://php.net/manual/en/sessionhandlerinterface.destroy.php
     * @since PHP 5 >= 5.4.0, PHP 7, PHP 8
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
