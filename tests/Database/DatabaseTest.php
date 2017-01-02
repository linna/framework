<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

use Linna\Database\Database;
use Linna\Database\MysqlPDOAdapter;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    public function testConnection()
    {
        $MysqlAdapter = new MysqlPDOAdapter(
        $GLOBALS['db_type'].':host='.$GLOBALS['db_host'].';dbname='.$GLOBALS['db_name'].';charset=utf8mb4',
        $GLOBALS['db_username'],
        $GLOBALS['db_password'],
        array(\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING)
        );

        $dbase = new Database($MysqlAdapter);
        
        $this->assertInstanceOf(PDO::class, $dbase->connect());
    }
}
