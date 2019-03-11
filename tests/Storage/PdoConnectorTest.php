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

use Linna\Storage\Connectors\PdoConnector;
use PDO;
use PDOException;
use PHPUnit\Framework\TestCase;

/**
 * Pdo Connector Test
 */
class PdoConnectorTest extends TestCase
{
    /**
     * Test connection.
     *
     * @return void
     */
    public function testConnection(): void
    {
        $options = [
            'dsn'      => $GLOBALS['pdo_mysql_dsn'],
            'user'     => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'options'  => [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT         => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
            ],
        ];

        $this->assertInstanceOf(PDO::class, (new PdoConnector($options))->getResource());
    }

    /**
     * Connection data provider.
     *
     * @return array
     */
    public function connectionDataProvider(): array
    {
        return [
            ['0', $GLOBALS['pdo_mysql_user'], $GLOBALS['pdo_mysql_password']],
            [$GLOBALS['pdo_mysql_dsn'], '', $GLOBALS['pdo_mysql_password']],
            [$GLOBALS['pdo_mysql_dsn'], $GLOBALS['pdo_mysql_user'], 'bad_password'],
        ];
    }

    /**
     * Test fail connection.
     *
     * @dataProvider connectionDataProvider
     *
     * @return void
     */
    public function testFailConnection(string $dsn, string $user, string $password): void
    {
        $this->expectException(PDOException::class);
        
        $options = [
            'dsn'      => $dsn,
            'user'     => $user,
            'password' => $password,
            'options'  => [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT         => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
            ],
        ];

        (new PdoConnector($options))->getResource();
    }
}
