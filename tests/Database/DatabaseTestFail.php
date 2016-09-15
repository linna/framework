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
define('DB_PASS', 'badpass');
define('DB_CHARSET', 'utf8mb4');

use Linna\Database\Database;

class DatabaseTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedExceptionMessageRegExp #SQLSTATE[HY000].*#
     */
    public function testFailConnection()
    {
        $dbase = Database::connect();
    }
}