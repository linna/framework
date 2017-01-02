<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

use Linna\Database\MysqlPDOAdapter;
use PHPUnit\Framework\TestCase;

class MysqlPDOAdapterTest extends TestCase
{
    public function testConnection()
    {
        $MysqlAdapter = new MysqlPDOAdapter(
        $GLOBALS['db_type'].':host='.$GLOBALS['db_host'].';dbname='.$GLOBALS['db_name'].';charset=utf8mb4',
        $GLOBALS['db_username'],
        $GLOBALS['db_password'],
        array(\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING)
        );

        $this->assertInstanceOf(PDO::class, $MysqlAdapter->getResource());
    }
    
    /**
     * @outputBuffering disabled
     */
    public function testFailConnection()
    {
        ob_start();
        
        $MysqlAdapter1 = new MysqlPDOAdapter(
        '',
        $GLOBALS['db_username'],
        $GLOBALS['db_password'],
        array(\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING)
        );
        
        $resource = $MysqlAdapter1->getResource();
        
        $message = ob_get_clean();
        
        $this->assertEquals(null, $resource);
        $this->assertEquals('Connection Fail: invalid data source name', $message);
    }
}
