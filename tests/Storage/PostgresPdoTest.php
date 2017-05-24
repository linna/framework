<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Storage\PostgresqlPdoStorage;
use PHPUnit\Framework\TestCase;

class PostgresqlPdoStorageTest extends TestCase
{
    public function testConnection()
    {
        $PostgresqlPdoAdapter = new PostgresqlPdoStorage(
            $GLOBALS['pdo_pgsql_dsn'],
            $GLOBALS['pdo_pgsql_user'],
            $GLOBALS['pdo_pgsql_password'],
            [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, 
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ]
        );

        $this->assertInstanceOf(PDO::class, $PostgresqlPdoAdapter->getResource());
    }

    public function connectionDataProvider()
    {
        return [
            ['0', $GLOBALS['pdo_pgsql_user'], $GLOBALS['pdo_pgsql_password']],
            [$GLOBALS['pdo_pgsql_dsn'], '', $GLOBALS['pdo_pgsql_password']],
            //[$GLOBALS['pdo_pgsql_dsn'], $GLOBALS['pdo_pgsql_user'], ''],
            //[$GLOBALS['pdo_pgsql_dsn'], $GLOBALS['pdo_pgsql_user'], 'bad_password'],
        ];
    }

    /**
     * @dataProvider connectionDataProvider
     * @expectedException PDOException
     */
    public function testFailConnection($dsn, $user, $password)
    {
        (new PostgresqlPdoStorage($dsn, $user, $password,[
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, 
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ]))->getResource();
    }
}
