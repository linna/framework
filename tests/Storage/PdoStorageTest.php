<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Storage\PdoStorage;
use Linna\Storage\ExtendedPDO;
use PHPUnit\Framework\TestCase;

/**
 * Pdo Storage Test
 */
class PdoStorageTest extends TestCase
{
    public function testConnection()
    {
        $options = [
            'dsn'      => $GLOBALS['pdo_mysql_dsn'],
            'user'     => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'options'  => [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_PERSISTENT         => false,
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
            ],
        ];

        $this->assertInstanceOf(PDO::class, (new PdoStorage($options))->getResource());
    }

    /**
     *
     * @return array
     */
    public function connectionDataProvider() : array
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
     * @expectedException PDOException
     */
    public function testFailConnection(string $dsn, string $user, string $password)
    {
        $options = [
            'dsn'      => $dsn,
            'user'     => $user,
            'password' => $password,
            'options'  => [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_PERSISTENT         => false,
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
            ],
        ];

        (new PdoStorage($options))->getResource();
    }
}
