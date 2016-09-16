<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

use Linna\Database\Database;
use Linna\Database\MysqlPDOAdapter;
use Linna\Session\DatabaseSessionHandler;
use Linna\Session\Session;
use PHPUnit\Framework\TestCase;

class DatabaseSessionHandlerTest extends TestCase
{
    protected $session;
    
    protected function initialize()
    {
        $MysqlAdapter = new MysqlPDOAdapter(
        'mysql:host=localhost;dbname=test;charset=utf8mb4', 
        'root', 
        '', 
        array(\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING)
        );

        $dbase = new Database($MysqlAdapter);
        
        $sessionHandler = new DatabaseSessionHandler($dbase);
        
        Session::setSessionHandler($sessionHandler);
        //se session options
        Session::withOptions(array(
            'expire' => 1800,
            'cookieDomain' => '/',
            'cookiePath' => '/',
            'cookieSecure' => false,
            'cookieHttpOnly' => true
        ));
        
        $this->session = Session::getInstance();
        
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testSession()
    {
        $this->initialize();
        
        $this->session->testdata = 'test';
        
        $this->assertEquals('test', $this->session->testdata);
        
        $this->session->testdata = 'new test';
        
        $this->assertEquals('new test', $this->session->testdata);
        
        unset($this->session->testdata);
        
        $this->assertEquals(false, $this->session->testdata);
        
        session_write_close();
    }
}