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
    protected $session;

    /**
     * @var MysqlPdoSessionHandler The session handler class.
     */
    protected $handler;

    /**
     * @var ExtendedPDO The pdo class.
     */
    protected $pdo;

    /**
     * Setup.
     */
    public function setUp(): void
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

        $handler = new MysqlPdoSessionHandler($pdo);
        $session = new Session(['expire' => 10]);

        $session->setSessionHandler($handler);

        $this->pdo = $pdo;

        $this->handler = $handler;

        $this->session = $session;
    }

    /**
     * Test Session Start.
     *
     * @runInSeparateProcess
     */
    public function testSessionStart(): void
    {
        $session = $this->session;

        $this->assertEquals(1, $session->status);

        $session->start();

        $this->assertEquals(2, $session->status);

        $session->destroy();
    }

    /**
     * Test session commit.
     *
     * @runInSeparateProcess
     */
    public function testSessionCommit(): void
    {
        $session = $this->session;
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
     */
    public function testSessionDestroy(): void
    {
        $session = $this->session;

        $session->start();
        $session['fooData'] = 'fooData';

        $this->assertEquals(2, $session->status);
        $this->assertEquals(session_id(), $session->id);
        $this->assertEquals('fooData', $session['fooData']);

        $session->destroy();

        $this->assertEquals(1, $session->status);
        $this->assertEquals('', $session->id);
        $this->assertFalse($session['fooData']);
    }

    /**
     * Test session regenerate.
     *
     * @runInSeparateProcess
     */
    public function testSessionRegenerate(): void
    {
        $session = $this->session;

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
     */
    public function testSessionExpired(): void
    {
        $session = $this->session;

        $session->start();

        $session_id = $session->id;

        $session->time = $session->time - 1800;

        $session->commit();

        $session->setSessionHandler($this->handler);

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
     */
    public function testGc(): void
    {
        $this->pdo->query('DELETE FROM session');

        $pdos = $this->pdo->prepare('INSERT INTO session (session_id, session_data) VALUES (:session_id, :session_data)');

        for ($i = 0; $i < 10; $i++) {
            $sessionId = md5((string) $i);
            $time = time() - $i;
            $data = 'time|i:'.$time.';';

            $pdos->bindParam(':session_id', $sessionId, PDO::PARAM_STR);
            $pdos->bindParam(':session_data', $data, PDO::PARAM_STR);
            $pdos->execute();
        }

        $this->handler->gc(-1);

        $pdos = $this->pdo->prepare('SELECT * FROM session');
        $pdos->execute();

        $this->assertEquals(0, $pdos->rowCount());
    }
}
