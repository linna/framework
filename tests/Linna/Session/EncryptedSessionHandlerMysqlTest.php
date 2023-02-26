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
use Linna\Storage\StorageFactory;
use PDO;
use PHPUnit\Framework\TestCase;

/**
 * Encrypted Session Handler Test with Mysql session handler.
 */
class EncryptedSessionHandlerMysqlTest extends TestCase
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
            'dsn'      => $GLOBALS['pdo_mysql_dsn'],
            'user'     => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'options'  => [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT         => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
            ],
        ];

        $pdo = (new StorageFactory('pdo', $options))->get();

        // from this class
        self::$pdo = $pdo;

        // from the pdo handler trait, static
        self::$query = new PdoSessionHandlerMysqlQuery();

        $crypto = new SecretKeyCrypto();
        //the handler to be decorated
        $handler = new PdoSessionHandler($pdo, new PdoSessionHandlerMysqlQuery());

        $addtionaData = 'session_test';
        $nonce = SecretKeyCrypto::generateNonce();
        $key = SecretKeyCrypto::generateKey();

        self::$handler = new EncryptedSessionHandler($crypto, $handler, $addtionaData, $nonce, $key);
        self::$session = new Session(expire: 10);

        // from the pdo handler trait, non static
        self::$querySelect = 'SELECT * FROM session';
        self::$queryDelete = 'DELETE FROM session';
    }
}
