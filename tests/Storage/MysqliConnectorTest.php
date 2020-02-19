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

use Linna\Storage\Connectors\MysqliConnector;
use mysqli;
use mysqli_sql_exception;
use PHPUnit\Framework\TestCase;

/**
 * Mysqli Connector Test
 */
class MysqliConnectorTest extends TestCase
{
    /**
     * Test connection.
     *
     * @return void
     */
    public function testConnection(): void
    {
        $options = [
            'host'     => '127.0.0.1',
            'user'     => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'database' => 'linna_db',
            'port'     => 3306,
        ];

        $this->assertInstanceOf(mysqli::class, (new MysqliConnector($options))->getResource());
    }

    /**
     * Connection data provider.
     *
     * @return array
     */
    public function connectionDataProvider(): array
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
     *
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $database
     * @param int    $port
     *
     * @return void
     */
    public function testFailConnection(
        string $host,
        string $user,
        string $password,
        string $database,
        int $port
    ): void {
        $this->expectException(mysqli_sql_exception::class);

        $options = [
            'host'     => $host,
            'user'     => $user,
            'password' => $password,
            'database' => $database,
            'port'     => $port,
        ];

        (new MysqliConnector($options))->getResource();
    }
}
