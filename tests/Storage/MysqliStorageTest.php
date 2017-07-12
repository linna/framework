<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Storage\MysqliStorage;
use PHPUnit\Framework\TestCase;

/**
 * Mysqli Storage Test
 */
class MysqliStorageTest extends TestCase
{
    /**
     * Test connection.
     */
    public function testConnection()
    {
        $options = [
            'host'     => '127.0.0.1',
            'user'     => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'database' => 'linna_db',
            'port'     => 3306,
        ];

        $this->assertInstanceOf(mysqli::class, (new MysqliStorage($options))->getResource());
    }

    /**
     * Connection data provider.
     * 
     * @return array
     */
    public function connectionDataProvider() : array
    {
        return [
            ['a.a.a.a', $GLOBALS['pdo_mysql_user'], $GLOBALS['pdo_mysql_password'], 'linna_db', 3306],
            ['127.0.0.1', '', $GLOBALS['pdo_mysql_password'], 'linna_db', 3306],
            ['127.0.0.1', $GLOBALS['pdo_mysql_user'], 'bad_password', 'linna_db', 3306],
            ['127.0.0.1', $GLOBALS['pdo_mysql_user'], $GLOBALS['pdo_mysql_password'], 'otherdb', 3306],
            ['127.0.0.1', $GLOBALS['pdo_mysql_user'], $GLOBALS['pdo_mysql_password'], 'linna_db', 3305],
        ];
    }

    /**
     * Test fail connection.
     * 
     * @dataProvider connectionDataProvider
     * @expectedException mysqli_sql_exception
     */
    public function testFailConnection(
            string $host, 
            string $user, 
            string $password, 
            string $database, 
            int $port
            )
    {
        $options = [
            'host'     => $host,
            'user'     => $user,
            'password' => $password,
            'database' => $database,
            'port'     => $port,
        ];

        (new MysqliStorage($options))->getResource();
    }
}
