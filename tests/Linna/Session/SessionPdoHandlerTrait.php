<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2020, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Session;

use Linna\Storage\ExtendedPDO;
use PDO;

/**
 * Session Handler trait.
 */
trait SessionPdoHandlerTrait
{
    use SessionHandlerTrait;

    /** @var ExtendedPDO The pdo class. */
    protected static $pdo;

    /** @var PdoSessionHandlerQueryInterface The object that contains queries for a specific database. */
    protected static PdoSessionHandlerQueryInterface $query;

    /** @var string The query to select all session. */
    protected static string $querySelect = '';

    /** @var string The query to delete all session. */
    protected static string $queryDelete = '';

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
        $this->assertEquals(\session_id(), $session->getSessionId());
        $this->assertEquals('fooData', $session['fooData']);

        $oldSessionId = $session->getSessionId();

        $session->commit();

        //check for session data on database
        $pdos = self::$pdo->queryWithParam(
            self::$query::QUERY_READ,
            [[':id', $oldSessionId, PDO::PARAM_STR]]
        );

        $this->assertEquals(1, $pdos->rowCount());

        $session->start();

        $this->assertEquals(2, $session->getStatus());
        $this->assertEquals($oldSessionId, $session->getSessionId());

        $session->destroy();

        //check for session data deletion on database
        $pdosDestroyed = self::$pdo->queryWithParam(
            self::$query::QUERY_READ,
            [[':id', $oldSessionId, PDO::PARAM_STR]]
        );

        $pdosDestroyed->execute();

        $this->assertEquals(0, $pdosDestroyed->rowCount());

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
        self::$pdo->query(self::$queryDelete);

        $pdos = self::$pdo->prepare(self::$query::QUERY_WRITE);

        for ($i = 0; $i < 10; $i++) {
            $sessionId = \md5((string) $i);
            $time = \time() - $i;
            $data = 'time|i:'.$time.';';

            $pdos->bindParam(':id', $sessionId, PDO::PARAM_STR);
            $pdos->bindParam(':data', $data, PDO::PARAM_STR);
            $pdos->execute();
        }

        self::$handler->gc(-10);

        $pdos = self::$pdo->prepare(self::$querySelect);
        $pdos->execute();

        $this->assertEquals(0, $pdos->rowCount());
    }
}
