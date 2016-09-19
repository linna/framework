<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

use Linna\Session\MemcachedSessionHandler;
use Linna\Session\Session;
use PHPUnit\Framework\TestCase;

class MemcachedSessionHandlerTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testSession()
    {
        if (! class_exists('Memcached')) {
            $this->markTestSkipped('Memcached module not installed');
        }
        
        //create memcached instance
        $memcached = new Memcached();
        //connect to memcached server
        $memcached->addServer($GLOBALS['mem_host'], $GLOBALS['mem_port']);
        
        $sessionHandler = new MemcachedSessionHandler($memcached);

        Session::setSessionHandler($sessionHandler);
        //se session options
        Session::withOptions(array(
            'expire' => 8,
            'cookieDomain' => '/',
            'cookiePath' => '/',
            'cookieSecure' => false,
            'cookieHttpOnly' => true
        ));
        
        $session = Session::getInstance();

        $session->testdata = 'test';

        $this->assertEquals('test', $session->testdata);

        $session->testdata = 'new test';

        $this->assertEquals('new test', $session->testdata);

        unset($session->testdata);

        $this->assertEquals(false, $session->testdata);

        session_write_close();
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testGc()
    {
        if (! class_exists('Memcached')) {
            $this->markTestSkipped('Memcached module not installed');
        }
        
        //create memcached instance
        $memcached = new Memcached();
        //connect to memcached server
        $memcached->addServer('localhost', 11211);
        
        $sessionHandler = new MemcachedSessionHandler($memcached);

        $test = $sessionHandler->gc(0);
        
        $this->assertEquals(true, $test);
    }
}
