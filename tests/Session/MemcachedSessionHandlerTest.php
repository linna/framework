<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
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
        
        $sessionHandler = new MemcachedSessionHandler($memcached, 1800);

        $session = new Session();
        
        $session->setSessionHandler($sessionHandler);
        
        $session->start();
        
        $session->testdata = 'test';

        $this->assertEquals('test', $session->testdata);

        $session->testdata = 'new test';

        $this->assertEquals('new test', $session->testdata);

        unset($session->testdata);

        $this->assertEquals(false, $session->testdata);

        $session->commit();
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testExpiredSession()
    {
        if (! class_exists('Memcached')) {
            $this->markTestSkipped('Memcached module not installed');
        }
        
        //create memcached instance
        $memcached = new Memcached();
        //connect to memcached server
        $memcached->addServer($GLOBALS['mem_host'], $GLOBALS['mem_port']);
        
        $sessionHandler = new MemcachedSessionHandler($memcached, 8);

        $session = new Session(['expire' => 8]);
        
        $session->setSessionHandler($sessionHandler);
        $session->start();
        $session_id = $session->id;
        
        $session->time = $session->time - 1800;
        
        $session->commit();
        
        $session->setSessionHandler($sessionHandler);
        $session->start();
        $session_id2 = $session->id;
        
        $test = ($session_id === $session_id2) ? 1 : 0;

        $this->assertEquals(0, $test);
        
        $session->destroy();
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
        
        $sessionHandler = new MemcachedSessionHandler($memcached, 8);

        $test = $sessionHandler->gc(0);
        
        $this->assertEquals(true, $test);
    }
}
