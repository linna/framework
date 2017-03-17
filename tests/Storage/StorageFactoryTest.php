<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Storage\MongoDbAdapter;
use Linna\Storage\MysqliAdapter;
use Linna\Storage\MysqlPdoAdapter;
use Linna\Storage\StorageFactory;
use MongoDB\Client;
use PHPUnit\Framework\TestCase;

class StorageFactoryTest extends TestCase
{
    protected $factory;

    public function setUp()
    {
        $this->factory = new StorageFactory();
    }

    public function testCreateMysqlPdo()
    {
        $options = [
            'dsn'      => $GLOBALS['pdo_mysql_dsn'],
            'user'     => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'options'  => [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING],
        ];

        $adapter = $this->factory->createConnection('mysqlpdo', $options);

        $this->assertInstanceOf(MysqlPdoAdapter::class, $adapter);
        $this->assertInstanceOf(\PDO::class, $adapter->getResource());
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

        $adapter = $this->factory->createConnection('mysqli', $options);

        $this->assertInstanceOf(MysqliAdapter::class, $adapter);
        $this->assertInstanceOf(\mysqli::class, $adapter->getResource());
    }

    public function testCreateMongoDb()
    {
        $options = [
            'uri'           => 'mongodb://localhost:27017',
            'uriOptions'    => [],
            'driverOptions' => [],
        ];

        $adapter = $this->factory->createConnection('mongodb', $options);

        $this->assertInstanceOf(MongoDbAdapter::class, $adapter);
        $this->assertInstanceOf(Client::class, $adapter->getResource());
    }

    public function testUnsupportedAdapter()
    {
        $this->expectException(InvalidArgumentException::class);
        $unsupportedAdapter = $this->factory->createConnection('unsupported', []);
    }
}
