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

use Linna\Session\MysqlPdoSessionHandler;
use Linna\Session\Session;
use Linna\Storage\StorageFactory;
use Linna\Storage\ExtendedPDO;
use PDO;
use PHPUnit\Framework\TestCase;

/**
 * Mysql Pdo Session Handler Test
 */
class MysqlPdoSessionHandlerTest extends TestCase
{
    /**
     * @var Session The session class.
     */
    protected static $session;

    /**
     * @var MysqlPdoSessionHandler The session handler class.
     */
    protected static $handler;

    /**
     * @var ExtendedPDO The pdo class.
     */
    protected static $pdo;

    /**
     * Set up before class.
     *
     * @requires extension memcached
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        $options = [
            'dsn'      => $GLOBALS['pdo_mysql_dsn'],
            'user'     => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'options'  => [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT         => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
            ],
        ];

        $pdo = (new StorageFactory('pdo', $options))->get();

        self::$handler = new MysqlPdoSessionHandler($pdo);
        self::$session = new Session(['expire' => 1800]);
        self::$pdo = $pdo;
    }

    /**
     * Tear down after class.
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        //closing PDO connection
        self::$pdo = null;
        self::$handler = null;
        self::$session = null;
    }

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

        $this->assertEquals($session->id, session_id());

        $session['fooData'] = 'fooData';

        $session->commit();

        $session->start();

        $this->assertEquals($session->id, session_id());
        $this->assertEquals('fooData', $session['fooData']);

        $session->destroy();
    }

    /**
     * Test session destroy.
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
        $this->assertEquals(session_id(), $session->id);
        $this->assertEquals('fooData', $session['fooData']);

        $oldSessionId = $session->id;

        $session->commit();

        //check for session data on database
        $pdos = self::$pdo->queryWithParam(
            'SELECT session_data FROM session WHERE session_id = :session_id',
            [[':session_id', $oldSessionId, PDO::PARAM_STR]]
        );

        $this->assertEquals(1, $pdos->rowCount());

        $session->start();

        $this->assertEquals(2, $session->status);
        $this->assertEquals($oldSessionId, $session->id);

        $session->destroy();

        //check for session data deletion on database
        $pdos = self::$pdo->queryWithParam(
            'SELECT session_data FROM session WHERE session_id = :session_id',
            [[':session_id', $oldSessionId, PDO::PARAM_STR]]
        );

        $pdos->execute();

        $this->assertEquals(0, $pdos->rowCount());

        $this->assertEquals(1, $session->status);
        $this->assertEquals('', $session->id);
        $this->assertFalse($session['fooData']);
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

        $sessionIdBefore = session_id();

        $this->assertEquals(2, $session->status);
        $this->assertEquals($sessionIdBefore, $session->id);
        $this->assertEquals('fooData', $session['fooData']);

        $session->regenerate();

        $sessionIdAfter = session_id();

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

    /**
     * Test garbage.
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testGc(): void
    {
        self::$pdo->query('DELETE FROM session');

        $pdos = self::$pdo->prepare('INSERT INTO session (session_id, session_data) VALUES (:session_id, :session_data)');

        for ($i = 0; $i < 10; $i++) {
            $sessionId = md5((string) $i);
            $time = time() - $i;
            $data = 'time|i:'.$time.';';

            $pdos->bindParam(':session_id', $sessionId, PDO::PARAM_STR);
            $pdos->bindParam(':session_data', $data, PDO::PARAM_STR);
            $pdos->execute();
        }

        self::$handler->gc(-1);

        $pdos = self::$pdo->prepare('SELECT * FROM session');
        $pdos->execute();

        $this->assertEquals(0, $pdos->rowCount());
    }
}
