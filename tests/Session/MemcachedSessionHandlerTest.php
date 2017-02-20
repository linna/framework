<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Session\MemcachedSessionHandler;
use Linna\Session\Session;
use PHPUnit\Framework\TestCase;

class MemcachedSessionHandlerTest extends TestCase
{
    protected $memcached;

    protected $session;

    protected $sessionHandler;

    public function setUp()
    {
        if (!class_exists('Memcached')) {
            return;
        }

        //create memcached instance
        $memcached = new Memcached();
        //connect to memcached server
        $memcached->addServer($GLOBALS['mem_host'], (int) $GLOBALS['mem_port']);

        $this->memcached = $memcached;

        $this->sessionHandler = new MemcachedSessionHandler($memcached, 5);

        $this->session = new Session(['expire' => 10]);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSessionStart()
    {
        if (!class_exists('Memcached')) {
            $this->markTestSkipped('Memcached module not installed');
        }

        $session = $this->session;

        $session->setSessionHandler($this->sessionHandler);

        $this->assertEquals(1, $session->status);

        $session->start();

        $this->assertEquals(2, $session->status);

        $session->destroy();
    }

    /**
     * @runInSeparateProcess
     */
    public function testExpiredSession()
    {
        if (!class_exists('Memcached')) {
            $this->markTestSkipped('Memcached module not installed');
        }

        $session = $this->session;

        $session->setSessionHandler($this->sessionHandler);

        $session->start();

        $session_id = $session->id;

        $session->time = $session->time - 1800;

        $session->commit();

        $session->setSessionHandler($this->sessionHandler);

        $session->start();

        $session2_id = $session->id;

        $this->assertEquals(false, ($session_id === $session2_id));
        $this->assertEquals(2, $session->status);

        $session->destroy();
    }

    /**
     * @runInSeparateProcess
     */
    public function testGc()
    {
        if (!class_exists('Memcached')) {
            $this->markTestSkipped('Memcached module not installed');
        }

        $test = $this->sessionHandler->gc(0);

        $this->assertEquals(true, $test);
    }
}
