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
use Linna\Session\DatabaseSessionHandler;
use Linna\Session\Session;

class DatabaseSessionHandlerTest extends PHPUnit_Framework_TestCase
{
    protected $session;
    
    protected function initialize()
    {
        $dbase = Database::connect();
        
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
        
        $this->session->testdata = 'pippo';
        //$dbase = Database::connect();
        
        
        
        //$this->assertInstanceOf(PDO::class, $dbase);
    }
}