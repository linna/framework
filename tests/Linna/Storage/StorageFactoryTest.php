<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Storage;

use InvalidArgumentException;
use Linna\Storage\StorageFactory;
use MongoDB\Client;
use mysqli;
use PDO;
use PgSql\Connection;
use PHPUnit\Framework\TestCase;

/**
 * Storage Factory Test
 */
class StorageFactoryTest extends TestCase
{
    /**
     * Test create postgre storage.
     *
     * @return void
     */
    public function testCreatePg(): void
    {
        $options = [
            'connection_string' => $GLOBALS['pgsql_connection_string'],
            'flags'             => 0,
        ];

        $this->assertInstanceOf(Connection::class, (new StorageFactory('pg', $options))->get());
    }

    /**
     * Test create pdo storage.
     *
     * @return void
     */
    public function testCreatePdo(): void
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

        $this->assertInstanceOf(PDO::class, (new StorageFactory('pdo', $options))->get());
    }

    /**
     * Test create mysqli storage.
     *
     * @return void
     */
    public function testCreateMysqlI(): void
    {
        $options = [
            'host'     => '127.0.0.1',
            'user'     => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'database' => 'linna_db',
            'port'     => 3306,
        ];

        $this->assertInstanceOf(mysqli::class, (new StorageFactory('mysqli', $options))->get());
    }

    /**
     * Test create mongodb storage.
     *
     * @return void
     */
    public function testCreateMongoDb(): void
    {
        $options = [
            'uri'           => 'mongodb://localhost:27017',
            'uriOptions'    => [],
            'driverOptions' => [],
        ];

        $this->assertInstanceOf(Client::class, (new StorageFactory('mongodb', $options))->get());
    }

    /**
     * Test unsupported storage.
     *
     * @return void
     */
    public function testUnsupportedAdapter(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("[] not supported.");

        (new StorageFactory('', []))->get();
    }
}
