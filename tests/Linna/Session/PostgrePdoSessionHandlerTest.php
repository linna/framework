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

use Linna\Storage\StorageFactory;
use PDO;
use PHPUnit\Framework\TestCase;

/**
 * Postgres Pdo Session Handler Test.
 */
class PostgrePdoSessionHandlerTest extends TestCase
{
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

        // from the trait, static
        self::$query = new PdoPostgreQuery();
        self::$handler = new PdoSessionHandler($pdo, new PdoPostgreQuery());
        self::$session = new Session(expire: 1800);

        // from the trait, non static
        self::$querySelect = 'SELECT * FROM public.session';
        self::$queryDelete = 'DELETE FROM public.session';
    }
}
