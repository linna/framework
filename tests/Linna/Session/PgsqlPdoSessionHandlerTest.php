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

//use Linna\Session\PgsqlPdoSessionHandler;
//use Linna\Session\Session;
use Linna\Storage\StorageFactory;
use Linna\Storage\ExtendedPDO;
use PDO;
use PHPUnit\Framework\TestCase;

/**
 * Postgres Pdo Session Handler Test
 */
class PgsqlPdoSessionHandlerTest extends TestCase
{
    use SessionHandlerTrait;

    /** @var ExtendedPDO The pdo class. */
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
            'dsn'      => $GLOBALS['pdo_pgsql_dsn'],
            'user'     => $GLOBALS['pdo_pgsql_user'],
            'password' => $GLOBALS['pdo_pgsql_password'],
            'options'  => [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT         => false,
            ],
        ];

        $pdo = (new StorageFactory('pdo', $options))->get();

        self::$handler = new PgsqlPdoSessionHandler($pdo);
        self::$session = new Session(expire: 1800);
        self::$pdo = $pdo;
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

        $this->assertEquals(2, $session->getStatus());
        $this->assertEquals(session_id(), $session->getSessionId());
        $this->assertEquals('fooData', $session['fooData']);

        $oldSessionId = $session->getSessionId();

        $session->commit();

        //check for session data on database
        $pdos = self::$pdo->queryWithParam(
            'SELECT session_data FROM public.session WHERE session_id = :session_id',
            [[':session_id', $oldSessionId, PDO::PARAM_STR]]
        );

        $this->assertEquals(1, $pdos->rowCount());

        $session->start();

        $this->assertEquals(2, $session->getStatus());
        $this->assertEquals($oldSessionId, $session->getSessionId());

        $session->destroy();

        //check for session data deletion on database
        $pdos = self::$pdo->queryWithParam(
            'SELECT session_data FROM public.session WHERE session_id = :session_id',
            [[':session_id', $oldSessionId, PDO::PARAM_STR]]
        );

        $pdos->execute();

        $this->assertEquals(0, $pdos->rowCount());

        $this->assertEquals(1, $session->getStatus());
        $this->assertEquals('', $session->getSessionId());
        $this->assertNull($session['fooData']);
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
        self::$pdo->query('DELETE FROM public.session');

        $pdos = self::$pdo->prepare('INSERT INTO public.session (session_id, session_data) VALUES (:session_id, :session_data)');

        for ($i = 0; $i < 10; $i++) {
            $sessionId = md5((string) $i);
            $time = time() - $i;
            $data = 'time|i:'.$time.';';

            $pdos->bindParam(':session_id', $sessionId, PDO::PARAM_STR);
            $pdos->bindParam(':session_data', $data, PDO::PARAM_STR);
            $pdos->execute();
        }

        self::$handler->gc(-50);

        $pdos = self::$pdo->prepare('SELECT * FROM public.session');
        $pdos->execute();

        $this->assertEquals(0, $pdos->rowCount());
    }
}
