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
            $GLOBALS['pdo_pgsql_password']
        );

        $this->assertInstanceOf(PDO::class, $PostgresqlPdoAdapter->getResource());
    }

    public function connectionDataProvider()
    {
        return [
            ['0', $GLOBALS['pdo_pgsql_user'], $GLOBALS['pdo_pgsql_password']],
            [$GLOBALS['pdo_pgsql_dsn'], '', $GLOBALS['pdo_pgsql_password']],
            [$GLOBALS['pdo_pgsql_dsn'], $GLOBALS['pdo_pgsql_user'], 'bad_password'],
        ];
    }

    /**
     * @dataProvider connectionDataProvider
     * @expectedException Exception
     */
    public function testFailConnection($dsn, $user, $password)
    {
        (new PostgresqlPdoStorage($dsn, $user, $password))->getResource();
    }
}
