<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Tests;

use Linna\Session\MemcachedSessionHandler;
use Linna\Session\Session;
use Memcached;
use PHPUnit\Framework\TestCase;

/**
 * Memcached Session Handler Test
 */
class MemcachedSessionHandlerTest extends TestCase
{
    /**
     * @var Session The session class.
     */
    protected static $session;

    /**
     * @var MemcachedSessionHandler The session handler class.
     */
    protected static $handler;

    /**
     * @var Memcached The memcached class.
     */
    protected static $memcached;

    /**
     * Set up before class.
     *
     * @requires extension memcached
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        $memcached = new Memcached();
        $memcached->addServer($GLOBALS['mem_host'], (int) $GLOBALS['mem_port']);

        self::$handler = new MemcachedSessionHandler($memcached, 5);
        self::$session = new Session(['expire' => 10]);
    }

    /**
     * Tear down after class.
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        self::$handler = null;
        self::$session = null;
    }

    /**
     * Setup.
     *
     * @requires extension memcached
     *
     * @return void
     */
    public function setUp(): void
    {
        self::$session->setSessionHandler(self::$handler);
    }


    /**
     * Test set session handler.
     *
     * @requires extension memcached
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testSetSessionHandler(): void
    {
        self::$session->setSessionHandler(self::$handler);

        $this->assertInstanceOf(MemcachedSessionHandler::class, self::$handler);
    }

    /**
     * Test Session Start.
     *
     * @requires extension memcached
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testSessionStart(): void
    {
        $session = self::$session;

        $this->assertEquals(1, $session->status);

        $session->start();

        $this->assertEquals(2, $session->status);

        $session->destroy();
    }

    /**
     * Test session commit.
     *
     * @requires extension memcached
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testSessionCommit(): void
    {
        $session = self::$session;
        $session->start();

        $this->assertEquals($session->id, \session_id());

        $session['fooData'] = 'fooData';

        $session->commit();

        $session->start();

        $this->assertEquals($session->id, \session_id());
        $this->assertEquals('fooData', $session['fooData']);

        $session->destroy();
    }

    /**
     * Test session destroy.
     *
     * @requires extension memcached
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testSessionDestroy(): void
    {
        $session = self::$session;

        $session->start();
        $session['fooData'] = 'fooData';

        $this->assertEquals(2, $session->status);
        $this->assertEquals(\session_id(), $session->id);
        $this->assertEquals('fooData', $session['fooData']);

        $session->destroy();

        $this->assertEquals(1, $session->status);
        $this->assertEquals('', $session->id);
        $this->assertFalse($session['fooData']);
    }

    /**
     * Test session regenerate.
     *
     * @requires extension memcached
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testSessionRegenerate(): void
    {
        $session = self::$session;

        $session->start();
        $session['fooData'] = 'fooData';

        $sessionIdBefore = \session_id();

        $this->assertEquals(2, $session->status);
        $this->assertEquals($sessionIdBefore, $session->id);
        $this->assertEquals('fooData', $session['fooData']);

        $session->regenerate();

        $sessionIdAfter = \session_id();

        $this->assertEquals(2, $session->status);
        $this->assertEquals($sessionIdAfter, $session->id);
        $this->assertNotEquals($sessionIdAfter, $sessionIdBefore);
        $this->assertEquals('fooData', $session['fooData']);

        $session->destroy();
    }

    /**
     * Test session expired.
     *
     * @requires extension memcached
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testSessionExpired(): void
    {
        $session = self::$session;

        $session->start();

        $session_id = $session->id;

        $session->time = $session->time - 1800;

        $session->commit();

        $session->setSessionHandler(self::$handler);

        $session->start();

        $session2_id = $session->id;

        $this->assertNotEquals($session_id, $session2_id);
        $this->assertEquals(2, $session->status);

        $session->destroy();
    }

    /**
     * Test garbage.
     *
     * @requires extension memcached
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testGc(): void
    {
        $this->assertTrue(self::$handler->gc(0));
    }
}
