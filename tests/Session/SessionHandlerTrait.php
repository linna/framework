<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2020, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Tests;

use Linna\Session\Session;
use SessionHandlerInterface;

/**
 * Session Handler trait.
 */
trait SessionHandlerTrait
{
    /**
     * @var Session The session class.
     */
    protected static Session $session;

    /**
     * @var SessionHandlerInterface The session handler concrete class.
     */
    protected static SessionHandlerInterface $handler;

    /**
     * Setup.
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

        $this->assertInstanceOf(SessionHandlerInterface::class, self::$handler);
    }

    /**
     * Test Session Start.
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
     * Test session regenerate.
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
}
