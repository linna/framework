<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2023, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Session;

use Linna\Crypto\SecretKeyCrypto;
use PHPUnit\Framework\TestCase;
use SessionHandler;

/**
 * Encrypted Session Handler Test with default file php session handler.
 */
class EncryptedSessionHandlerDefaultTest extends TestCase
{
    use SessionHandlerTrait;

    /**
     * Set up before class.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        $crypto = new SecretKeyCrypto();
        //the handler to be decorated
        $handler = new SessionHandler();

        $addtionaData = 'session_test';
        $nonce = SecretKeyCrypto::generateNonce();
        $key = SecretKeyCrypto::generateKey();

        self::$handler = new EncryptedSessionHandler($crypto, $handler, $addtionaData, $nonce, $key);
        self::$session = new Session(expire: 10);
    }
}
