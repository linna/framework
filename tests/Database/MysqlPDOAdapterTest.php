<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
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
        'mysql:host=localhost;dbname=test;charset=utf8mb4',
        'root',
        PASS,
        array(\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING)
        );

        $this->assertInstanceOf(PDO::class, $MysqlAdapter->getResource());
    }
    
    public function testFailConnection()
    {
        $MysqlAdapter1 = new MysqlPDOAdapter(
        '',
        'root',
        PASS,
        array(\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING)
        );
        
        $this->assertEquals(null, $MysqlAdapter1->getResource());
    }
}
