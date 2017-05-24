<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Storage\MysqlPdoStorage;
use PHPUnit\Framework\TestCase;

class MysqlPdoStorageTest extends TestCase
{
    public function testConnection()
    {
        $mysqlPdoAdapter = new MysqlPdoStorage(
            $GLOBALS['pdo_mysql_dsn'],
            $GLOBALS['pdo_mysql_user'],
            $GLOBALS['pdo_mysql_password'],
            [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, 
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_PERSISTENT => false,
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ]
        );

        $this->assertInstanceOf(PDO::class, $mysqlPdoAdapter->getResource());
    }

    public function connectionDataProvider()
    {
        return [
            ['0', $GLOBALS['pdo_mysql_user'], $GLOBALS['pdo_mysql_password']],
            [$GLOBALS['pdo_mysql_dsn'], '', $GLOBALS['pdo_mysql_password']],
            [$GLOBALS['pdo_mysql_dsn'], $GLOBALS['pdo_mysql_user'], ''],
            [$GLOBALS['pdo_mysql_dsn'], $GLOBALS['pdo_mysql_user'], 'bad_password'],
        ];
    }

    /**
     * @dataProvider connectionDataProvider
     */
    public function testFailConnection($dsn, $user, $password)
    {
        (new MysqlPdoStorage($dsn, $user, $password, [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, 
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_PERSISTENT => false,
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ]))->getResource();
    }
}
