<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Authentication;

//use Linna\Authentication\EnhancedAuthenticationMapper;
//use Linna\Authentication\LoginAttempt;
use Linna\Storage\ExtendedPDO;
use Linna\Storage\StorageFactory;
use PDO;
use PHPUnit\Framework\TestCase;

/**
 * Login Attempt Test.
 */
class LoginAttemptTest extends TestCase
{
    /**
     * @var ExtendedPDO Persistent storage connection.
     */
    protected static ExtendedPDO $pdo;

    /**
     * @var EnhancedAuthenticationMapper The enhanced authentication mapper class
     */
    protected static EnhancedAuthenticationMapper $enhancedAuthenticationMapper;

    /**
     * Set up before class.
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
        $enhancedAuthenticationMapper = new EnhancedAuthenticationMapper($pdo);

        self::$pdo = $pdo;
        self::$enhancedAuthenticationMapper = $enhancedAuthenticationMapper;

        self::loginClean();
    }

    /**
     * Tear down after class.
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        self::loginClean();

        //self::$pdo = null;
        //self::$enhancedAuthenticationMapper = null;
    }

    /**
     * Remove record from login_attemp table.
     *
     * @return void
     */
    protected static function loginClean(): void
    {
        self::$enhancedAuthenticationMapper->deleteOldLoginAttempts(-86400);
    }

    /**
     * Test login Attempt.
     *
     * @return void
     */
    public function testLoginAttempt(): void
    {
        /** @var \Linna\Authentication\LoginAttempt Login Attempt. */
        $loginAttempt = self::$enhancedAuthenticationMapper->create();
        $loginAttempt->userName = 'root';
        $loginAttempt->sessionId = 'mbvi2lgdpcj6vp3qemh2estei2';
        $loginAttempt->ipAddress = '192.168.1.2';
        $loginAttempt->when = \date('YmdHis', \time());

        $this->assertSame(null, $loginAttempt->getId());

        self::$enhancedAuthenticationMapper->save($loginAttempt);

        $this->assertIsInt($loginAttempt->getId());
        $this->assertGreaterThan(0, $loginAttempt->getId());

        $loginAttemptFromDB = self::$enhancedAuthenticationMapper->fetchById($loginAttempt->getId());

        $this->assertInstanceOf(LoginAttempt::class, $loginAttemptFromDB);

        $this->assertIsInt($loginAttemptFromDB->getId());
        $this->assertGreaterThan(0, $loginAttemptFromDB->getId());
    }
}
