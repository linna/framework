<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Storage;

use Linna\Storage\Connectors\PdoConnector;
use PDO;
use PDOException;
use PHPUnit\Framework\TestCase;
use Linna\TestHelper\Pdo\PdoOptionsFactory;

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
        /*$options = [
            'dsn'      => $GLOBALS['pdo_mysql_dsn'],
            'user'     => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'options'  => [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT         => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
            ],
        ];*/

        $this->assertInstanceOf(PDO::class, (new PdoConnector(PdoOptionsFactory::getOptions()))->getResource());
    }

    /**
     * Connection data provider.
     *
     * @return array
     */
    public static function connectionDataProvider(): array
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
     * @param string $dsn
     * @param string $user
     * @param string $password
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
