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
use Linna\Storage\StorageFactory;
use PDO;
use PHPUnit\Framework\TestCase;

/**
 * Encrypted Session Handler Test with PostgreSQL session handler.
 */
class EncryptedSessionHandlerPostgreTest extends TestCase
{
    use SessionHandlerTrait;
    use SessionPdoHandlerTrait;

    /**
     * Set up before class.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        $options = [
            'dsn'      => $GLOBALS['pdo_pgsql_dsn'],
            'user'     => $GLOBALS['pdo_pgsql_user'],
            'password' => $GLOBALS['pdo_pgsql_password'],
            'options'  => [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT         => false,
            ],
        ];

        $pdo = (new StorageFactory('pdo', $options))->get();

        // from this class
        self::$pdo = $pdo;

        // from the pdo handler trait, static
        self::$query = new PdoSessionHandlerPostgreQuery();

        $crypto = new SecretKeyCrypto();
        //the handler to be decorated
        $handler = new PdoSessionHandler($pdo, new PdoSessionHandlerPostgreQuery());

        $addtionaData = 'session_test';
        $nonce = SecretKeyCrypto::generateNonce();
        $key = SecretKeyCrypto::generateKey();

        self::$handler = new EncryptedSessionHandler($crypto, $handler, $addtionaData, $nonce, $key);
        self::$session = new Session(expire: 10);

        // from the pdo handler trait, non static
        self::$querySelect = 'SELECT * FROM public.session';
        self::$queryDelete = 'DELETE FROM public.session';
    }
}
