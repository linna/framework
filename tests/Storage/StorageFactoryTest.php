<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Storage\MongoDbStorage;
use Linna\Storage\MysqliStorage;
use Linna\Storage\PdoStorage;
use Linna\Storage\StorageFactory;
use MongoDB\Client;
use PHPUnit\Framework\TestCase;

/**
 * Storage Factory Test
 */
class StorageFactoryTest extends TestCase
{
    /**
     * Test create pdo storage.
     */
    public function testCreatePdo()
    {
        $options = [
            'dsn'      => $GLOBALS['pdo_mysql_dsn'],
            'user'     => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'options'  => [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_PERSISTENT         => false,
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
            ],
        ];

        $driver = (new StorageFactory('pdo', $options))->getConnection();

        $this->assertInstanceOf(PdoStorage::class, $driver);
        $this->assertInstanceOf(\PDO::class, $driver->getResource());
    }

    /**
     * Test create mysqli storage.
     */
    public function testCreateMysqlI()
    {
        $options = [
            'host'     => '127.0.0.1',
            'user'     => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'database' => 'linna_db',
            'port'     => 3306,
        ];

        $driver = (new StorageFactory('mysqli', $options))->getConnection();

        $this->assertInstanceOf(MysqliStorage::class, $driver);
        $this->assertInstanceOf(\mysqli::class, $driver->getResource());
    }

    /**
     * Test create mongodb storage.
     */
    public function testCreateMongoDb()
    {
        $options = [
            'uri'           => 'mongodb://localhost:27017',
            'uriOptions'    => [],
            'driverOptions' => [],
        ];

        $driver = (new StorageFactory('mongodb', $options))->getConnection();

        $this->assertInstanceOf(MongoDbStorage::class, $driver);
        $this->assertInstanceOf(Client::class, $driver->getResource());
    }

    /**
     * Test unsupported storage.
     * 
     * @expectedException InvalidArgumentException
     */
    public function testUnsupportedAdapter()
    {
        (new StorageFactory('', []))->getConnection();
    }
}
