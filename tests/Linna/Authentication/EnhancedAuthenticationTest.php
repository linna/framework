<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Authentication;

use Linna\Session\Session;
use Linna\Storage\ExtendedPDO;
use Linna\Storage\StorageFactory;
use PHPUnit\Framework\TestCase;
use TypeError;
use Linna\TestHelper\Pdo\PdoOptionsFactory;

/**
 * Enhanced Authentication Test.
 */
class EnhancedAuthenticationTest extends TestCase
{
    /** @var ExtendedPDO Persistent storage connection. */
    protected static ExtendedPDO $pdo;

    /** @var Session The session class. */
    protected static Session $session;

    /** @var Password The password class. */
    protected static Password $password;

    /** @var EnhancedAuthentication The enhanced authentication class */
    protected static EnhancedAuthentication $enhancedAuthentication;

    /** @var EnhancedAuthenticationMapper The enhanced authentication mapper class */
    protected static EnhancedAuthenticationMapper $enhancedAuthenticationMapper;

    /**
     * Set up before class.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        /*$options = [
            'dsn'      => $GLOBALS['pdo_mysql_dsn'],
            'user'     => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'options'  => [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT         => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
            ],
        ];*/

        $session = new Session();
        $password = new Password();
        $pdo = (new StorageFactory('pdo', PdoOptionsFactory::getOptions()))->get();
        $enhancedAuthenticationMapper = new EnhancedAuthenticationMapper($pdo);

        self::$pdo = $pdo;
        self::$password = $password;
        self::$session = $session;
        self::$enhancedAuthenticationMapper = $enhancedAuthenticationMapper;
        self::$enhancedAuthentication = new EnhancedAuthentication(
            session: $session,
            password: $password,
            mapper: $enhancedAuthenticationMapper
        );

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
     * Test new instance.
     *
     * @return void
     */
    public function testNewInstance(): void
    {
        $this->assertInstanceOf(
            EnhancedAuthentication::class,
            (
            new EnhancedAuthentication(
                self::$session,
                self::$password,
                self::$enhancedAuthenticationMapper
            )
            )
        );
    }

    /**
     * Test ge attempts with the same user.
     *
     * @return void
     */
    public function testGetAttemptsLeftWithSameUser(): void
    {
        $user = 'root';
        $sessionId = 'mbvi2lgdpcj6vp3qemh2estei2';
        $ipAddress = '192.168.1.2';

        for ($i = 0; $i < 4; $i++) {
            $this->storeLoginAttempt($user, $sessionId, $ipAddress);
        }

        $this->assertEquals(1, self::$enhancedAuthentication->getAttemptsLeftWithSameUser($user));
        $this->assertFalse(self::$enhancedAuthentication->isUserBanned($user));

        //pass the threshold
        $this->storeLoginAttempt($user, $sessionId, $ipAddress);

        $this->assertEquals(0, self::$enhancedAuthentication->getAttemptsLeftWithSameUser($user));
        $this->assertTrue(self::$enhancedAuthentication->isUserBanned($user));
    }

    /**
     * Test ge attempts with the same session.
     *
     * @return void
     */
    public function testGetAttemptsLeftWithSameSession(): void
    {
        $user = 'admin';
        $sessionId = 'vaqgvpochtif8gh888q6vnlch5';
        $ipAddress = '192.168.1.3';

        for ($i = 0; $i < 9; $i++) {
            $this->storeLoginAttempt($user, $sessionId, $ipAddress);
        }

        $this->assertEquals(1, self::$enhancedAuthentication->getAttemptsLeftWithSameSession($sessionId));
        $this->assertFalse(self::$enhancedAuthentication->isSessionBanned($sessionId));

        //pass the threshold
        $this->storeLoginAttempt($user, $sessionId, $ipAddress);

        $this->assertEquals(0, self::$enhancedAuthentication->getAttemptsLeftWithSameSession($sessionId));
        $this->assertTrue(self::$enhancedAuthentication->isSessionBanned($sessionId));
    }

    /**
     * Test ge attempts with the same ip.
     *
     * @return void
     */
    public function testGetAttemptsLeftWithSameIp(): void
    {
        $user = 'user';
        $sessionId = '3hto06tko273jjc1se0v1aqvvn';
        $ipAddress = '192.168.1.4';

        for ($i = 0; $i < 19; $i++) {
            $this->storeLoginAttempt($user, $sessionId, $ipAddress);
        }

        $this->assertEquals(1, self::$enhancedAuthentication->getAttemptsLeftWithSameIp($ipAddress));
        $this->assertFalse(self::$enhancedAuthentication->isIpBanned($ipAddress));

        //pass the threshold
        $this->storeLoginAttempt($user, $sessionId, $ipAddress);

        $this->assertEquals(0, self::$enhancedAuthentication->getAttemptsLeftWithSameIp($ipAddress));
        $this->assertTrue(self::$enhancedAuthentication->isIpBanned($ipAddress));
    }

    /**
     * Test login with misconfigured options.
     *
     * @return void
     */
    public function testLoginWithMisconfiguredOptions(): void
    {
        $this->expectException(TypeError::class);

        self::loginClean();

        $user = 'root';
        $sessionId = 'mbvi2lgdpcj6vp3qemh2estei2';
        $ipAddress = '192.168.1.2';

        $options = [
            'maxAttemptsForUserName'  => 'a',
            'maxAttemptsForSessionId' => 'a',
            'maxAttemptsForIpAddress' => 'a',
            'maxAttemptsForSecond'    => 40,
            'banTimeInSeconds'        => 900
        ];

        $enhancedAuthentication = new EnhancedAuthentication(self::$session, self::$password, self::$enhancedAuthenticationMapper, $options);

        $this->assertFalse($enhancedAuthentication->login($user, 'passwor', $user, '$2y$11$4IAn6SRaB0osPz8afZC5D.CmTrBGxnb5FQEygPjDirK9SWE/u8YuO', 1));

        $this->storeLoginAttempt($user, $sessionId, $ipAddress);

        //Access with user
        $this->assertEquals(0, $enhancedAuthentication->getAttemptsLeftWithSameUser($user));
        //Access with session
        $this->assertEquals(0, $enhancedAuthentication->getAttemptsLeftWithSameSession($sessionId));
        //Access with ip
        $this->assertEquals(0, $enhancedAuthentication->getAttemptsLeftWithSameIp($ipAddress));
    }

    /**
     * Store login attempts.
     *
     * @param string $user
     * @param string $sessionId
     * @param string $ipAddress
     *
     * @return void
     */
    protected function storeLoginAttempt(string &$user, string &$sessionId, string &$ipAddress): void
    {
        $loginAttempt = new LoginAttempt(
            userName:   $user,
            sessionId:  $sessionId,
            ipAddress:  $ipAddress,
            when:       \date_create_immutable()
        );

        self::$enhancedAuthenticationMapper->save($loginAttempt);
    }
}
