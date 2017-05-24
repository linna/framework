<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Storage\MysqliStorage;
use PHPUnit\Framework\TestCase;

class MysqliTest extends TestCase
{
    public function testConnection()
    {
        $mysqliAdapter = new MysqliStorage(
            '127.0.0.1',
            $GLOBALS['pdo_mysql_user'],
            $GLOBALS['pdo_mysql_password'],
            'linna_db',
            3306
        );

        $this->assertInstanceOf(mysqli::class, $mysqliAdapter->getResource());
    }

    public function connectionDataProvider()
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
     * @dataProvider connectionDataProvider
     * @expectedException mysqli_sql_exception
     */
    public function testFailConnection($host, $user, $password, $database, $port)
    {
        (new MysqliStorage($host, $user, $password, $database, $port))->getResource();
    }
}
