<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2023, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Session;

use Linna\Crypto\SecretKeyCrypto;
use SessionHandlerInterface;

/**
 * Decorate a session handler adding encryption layer.
 *
 * @link http://php.net/manual/en/class.sessionhandler.php
 */
class EncryptedSessionHandler implements SessionHandlerInterface
{
    /**
     * Class Constructor.
     *
     * @param SecretKeyCrypto         $crypto         The encryption provider.
     * @param SessionHandlerInterface $handler        The handler to decorate with encryption.
     * @param string                  $additionalData Additional, authenticated data. This is used in the verification of the
     *                                                authentication tag appended to the ciphertext, but it is not encrypted
     *                                                or stored in the ciphertext.
     * @param string                  $nonce          A number that must be only used once, per message. Use the static method
     *                                                <code>SecretKeyCrypto::generateNonce()</code> to generate a nonce having
     *                                                the correct size.
     * @param string                  $key            The key used to encrypt. Use the static method
     *                                                <code>SecretKeyCrypto::generateKey()</code> to generate a key having the
     *                                                correct size.
     */
    public function __construct(
        /** @var SecretKeyCrypto The encryption provider. */
        private SecretKeyCrypto $crypto,

        /** @var SessionHandlerInterface The handler to decorate with encryption. */
        private SessionHandlerInterface $handler,

        /**
         * @var string Additional, authenticated data. This is used in the verification of the authentication tag
         *             appended to the ciphertext, but it is not encrypted or stored in the ciphertext.
         */
        private string $additionalData,

        /**
         * @var string A number that must be only used once, per message. Use the static method
         *             <code>SecretKeyCrypto::generateNonce()</code> to generate a nonce having the correct size.
         */
        private string $nonce,

        /*
         * @var string The key used to encrypt. Use the static method <code>SecretKeyCrypto::generateKey()</code> to
         *             generate a key having the correct size.
         */
        private string $key
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
        return $this->handler->open($path, $name);
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
        return $this->handler->gc($max_lifetime);
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
        //get encrypted session data
        $ciphertext = $this->handler->read($id);

        //if session doesn't contain data, return a void string
        if (\strlen($ciphertext) === 0) {
            return "";
        }

        //decrypt session data
        $plaintext = $this->crypto->decrypt(\base64_decode($ciphertext), $this->additionalData, $this->nonce, $this->key);

        //return plaintext
        return $plaintext;
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
        //encrypt session data
        $ciphertext = $this->crypto->encrypt($data, $this->additionalData, $this->nonce, $this->key);

        //pass the encrypted data to original handler
        $result = $this->handler->write($id, \base64_encode($ciphertext));

        //return the result
        return $result;
    }

    /**
     * Close the session.
     *
     * <p>Closes the current session. This function is automatically executed when closing the session, or explicitly
     * via <code>session_write_close()</code>.</p>
     *
     * @return bool The return value (usually <b><code>true</code></b> on success, <b><code>false</code></b> on
     *              failure). Note this value is returned internally to PHP for processing.
     *
     * @link https://php.net/manual/en/sessionhandlerinterface.close.php
     * @since PHP 5 >= 5.4.0, PHP 7, PHP 8
     */
    public function close(): bool
    {
        return $this->handler->close();
    }

    /**
     * Destroy a session.
     *
     * <p>Destroys a session. Called by <code>session_regenerate_id()</code> (with $destroy = <b><code>true</code></b>),
     *  <code>session_destroy()</code> and when <code>session_decode()</code> fails.</p>
     *
     * @param string $id The session ID being destroyed.
     *
     * @return bool The return value (usually <b><code>true</code></b> on success, <b><code>false</code></b> on
     *              failure). Note this value is returned internally to PHP for processing.
     *
     * @link https://php.net/manual/en/sessionhandlerinterface.destroy.php
     * @since PHP 5 >= 5.4.0, PHP 7, PHP 8
     */
    public function destroy(string $id): bool
    {
        return $this->handler->destroy($id);
    }
}
