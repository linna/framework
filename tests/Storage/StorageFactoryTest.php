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
use Linna\Storage\MysqlPdoStorage;
use Linna\Storage\StorageFactory;
use MongoDB\Client;
use PHPUnit\Framework\TestCase;

class StorageFactoryTest extends TestCase
{
    public function testCreateMysqlPdo()
    {
        $options = [
            'dsn'      => $GLOBALS['pdo_mysql_dsn'],
            'user'     => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'options'  => [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING],
        ];       

        $driver = (new StorageFactory('mysqlpdo', $options))->getConnection();

        $this->assertInstanceOf(MysqlPdoStorage::class, $driver);
        $this->assertInstanceOf(\PDO::class, $driver->getResource());
    }

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
     * @expectedException InvalidArgumentException
     */
    public function testUnsupportedAdapter()
    {
        (new StorageFactory('', []))->getConnection();
    }
}
