<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Storage;

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
    public static function connectionDataProvider(): array
    {
        return [
            ['', $GLOBALS['pdo_mysql_user'], $GLOBALS['pdo_mysql_password'], 'linna_db', 3306],          //unknown address
            ['127.0.0.1', '', $GLOBALS['pdo_mysql_password'], 'linna_db', 3306],                         //bad user
            ['127.0.0.1', $GLOBALS['pdo_mysql_user'], '', 'linna_db', 3306],                             //bad password
            ['127.0.0.1', $GLOBALS['pdo_mysql_user'], $GLOBALS['pdo_mysql_password'], 'otherdb', 3306],  //wrong db
            ['127.0.0.1', $GLOBALS['pdo_mysql_user'], $GLOBALS['pdo_mysql_password'], 'linna_db', 3305], //wrong port
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
