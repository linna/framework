<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'test');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

use Linna\Database\Database;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    public function testConnection()
    {
        $dbase = Database::connect();
        
        $this->assertInstanceOf(PDO::class, $dbase);
    }
    
    public function testCloneConnection()
    {
        $dbase = Database::connect();
        
        $this->assertInstanceOf(PDO::class, $dbase);
        
        $otherdb = clone $dbase;
        
        $this->assertInstanceOf(PDO::class, $otherdb);
    }
}