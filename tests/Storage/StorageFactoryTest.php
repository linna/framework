<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Tests;

use Linna\Storage\StorageFactory;
use MongoDB\Client;
use PDO;
use PHPUnit\Framework\TestCase;

/**
 * Storage Factory Test
 */
class StorageFactoryTest extends TestCase
{
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

        $driver = (new StorageFactory('pdo', $options))->get();

        $this->assertInstanceOf(\PDO::class, $driver);
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

        $driver = (new StorageFactory('mysqli', $options))->get();

        $this->assertInstanceOf(\mysqli::class, $driver);
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

        $driver = (new StorageFactory('mongodb', $options))->get();

        $this->assertInstanceOf(Client::class, $driver);
    }

    /**
     * Test unsupported storage.
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage [] not supported.
     * 
     * @return void
     */
    public function testUnsupportedAdapter(): void
    {
        (new StorageFactory('', []))->get();
    }
}
