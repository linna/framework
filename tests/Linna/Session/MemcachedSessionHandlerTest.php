<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Session;

//use Linna\Session\MemcachedSessionHandler;
//use Linna\Session\Session;
use Memcached;
use PHPUnit\Framework\TestCase;

/**
 * Memcached Session Handler Test
 */
class MemcachedSessionHandlerTest extends TestCase
{
    use SessionHandlerTrait;

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
        self::$session = new Session(expire: 10);
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

        $this->assertEquals(2, $session->getStatus());
        $this->assertEquals(session_id(), $session->getSessionId());
        $this->assertEquals('fooData', $session['fooData']);

        $session->destroy();

        $this->assertEquals(1, $session->getStatus());
        $this->assertEquals('', $session->getSessionId());
        $this->assertNull($session['fooData']);
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
        $this->assertEquals(0, self::$handler->gc(0));
    }
}
