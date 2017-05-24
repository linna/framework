<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Session\MysqlPdoSessionHandler;
use Linna\Session\Session;
use Linna\Storage\MysqlPdoStorage;
use PHPUnit\Framework\TestCase;

class MysqlPdoSessionHandlerTest extends TestCase
{
    //protected $mysqlPdo;

    protected $session;

    protected $sessionHandler;

    public function setUp()
    {
        $pdo = new MysqlPdoStorage(
            $GLOBALS['pdo_mysql_dsn'],
            $GLOBALS['pdo_mysql_user'],
            $GLOBALS['pdo_mysql_password'],
            [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, 
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_PERSISTENT => false,
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ]
        );

        $this->sessionHandler = new MysqlPdoSessionHandler($pdo);

        $this->session = new Session(['expire' => 10]);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSession()
    {
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
        $pdo = new MysqlPdoStorage(
            $GLOBALS['pdo_mysql_dsn'],
            $GLOBALS['pdo_mysql_user'],
            $GLOBALS['pdo_mysql_password'],
            [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING]
        );

        $conn = $pdo->getResource();
        $conn->query('DELETE FROM session');

        $pdos = $conn->prepare('INSERT INTO session (session_id, session_data) VALUES (:session_id, :session_data)');

        for ($i = 0; $i < 10; $i++) {
            $sessionId = md5((string) $i);
            $time = time() - $i;
            $data = 'time|i:'.$time.';';

            $pdos->bindParam(':session_id', $sessionId, PDO::PARAM_STR);
            $pdos->bindParam(':session_data', $data, PDO::PARAM_STR);
            $pdos->execute();
        }

        $this->sessionHandler->gc(-1);

        $pdos = $conn->prepare('SELECT * FROM session');
        $pdos->execute();

        $this->assertEquals(0, $pdos->rowCount());
    }
}
