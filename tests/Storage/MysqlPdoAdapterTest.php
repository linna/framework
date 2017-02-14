<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Storage\MysqlPdoAdapter;
use PHPUnit\Framework\TestCase;

class MysqlPdoAdapterTest extends TestCase
{
    public function testConnection()
    {
        $mysqlPdoAdapter = new MysqlPdoAdapter(
            $GLOBALS['pdo_mysql_dsn'],
            $GLOBALS['pdo_mysql_user'],
            $GLOBALS['pdo_mysql_password'],
            [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING]
        );

        $this->assertInstanceOf(PDO::class, $mysqlPdoAdapter->getResource());
    }

    public function connectionDataProvider()
    {
        return [
            ['0', $GLOBALS['pdo_mysql_user'], $GLOBALS['pdo_mysql_password']],
            [$GLOBALS['pdo_mysql_dsn'], '', $GLOBALS['pdo_mysql_password']],
            [$GLOBALS['pdo_mysql_dsn'], $GLOBALS['pdo_mysql_user'], 'bad_password'],
        ];
    }

    /**
     * @dataProvider connectionDataProvider
     */
    public function testFailConnection($dsn, $user, $password)
    {
        $this->expectException(\Exception::class);

        $mysqlPdoAdapter = new MysqlPdoAdapter($dsn, $user, $password, []);

        $resource = $mysqlPdoAdapter->getResource();
    }
}
