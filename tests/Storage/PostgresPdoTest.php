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
        $options = [
            'dsn' => $GLOBALS['pdo_pgsql_dsn'],
            'user' => $GLOBALS['pdo_pgsql_user'],
            'password' => $GLOBALS['pdo_pgsql_password'],
            'options' => [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            ]
        ];

        $this->assertInstanceOf(PDO::class, (new PostgresqlPdoStorage($options))->getResource());
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
        $options = [
            'dsn' => $dsn,
            'user' => $user,
            'password' => $password,
            'options' => [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            ]  
        ];

        (new PostgresqlPdoStorage($options))->getResource();
    }
}
