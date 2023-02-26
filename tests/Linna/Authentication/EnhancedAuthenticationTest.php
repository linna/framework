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
        /*\var_dump(PdoOptionsFactory::getOptions());

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

        //self::$pdo = null;
        //self::$password = null;
        //self::$session = null;
        //self::$enhancedAuthenticationMapper = null;
        //self::$enhancedAuthentication = null;
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
     * Wrong arguments router class provider.
     *
     * @return array
     */
    public static function wrongCredentialProvider(): array
    {
        return [
            ['root', 'mbvi2lgdpcj6vp3qemh2estei2', '192.168.1.2', 4, 9, 19, false, false, false],
            ['root', 'mbvi2lgdpcj6vp3qemh2estei2', '192.168.1.2', 3, 8, 18, false, false, false],
            ['root', 'mbvi2lgdpcj6vp3qemh2estei2', '192.168.1.2', 2, 7, 17, false, false, false],
            ['root', 'mbvi2lgdpcj6vp3qemh2estei2', '192.168.1.2', 1, 6, 16, false, false, false],
            ['root', 'mbvi2lgdpcj6vp3qemh2estei2', '192.168.1.2', 0, 5, 15, true, false, false],
            ['root', 'mbvi2lgdpcj6vp3qemh2estei2', '192.168.1.2', 0, 4, 14, true, false, false],
            ['admin', 'mbvi2lgdpcj6vp3qemh2estei2', '192.168.1.2', 4, 3, 13, false, false, false],
            ['admin', 'mbvi2lgdpcj6vp3qemh2estei2', '192.168.1.2', 3, 2, 12, false, false, false],
            ['admin', 'mbvi2lgdpcj6vp3qemh2estei2', '192.168.1.2', 2, 1, 11, false, false, false],
            ['admin', 'mbvi2lgdpcj6vp3qemh2estei2', '192.168.1.2', 1, 0, 10, false, true, false],
            ['admin', 'mbvi2lgdpcj6vp3qemh2estei2', '192.168.1.2', 0, 0, 9, true, true, false],
            ['admin', 'mbvi2lgdpcj6vp3qemh2estei2', '192.168.1.2', 0, 0, 8, true, true, false],
            ['administrator', 'vaqgvpochtif8gh888q6vnlch5', '192.168.1.2', 4, 9, 7, false, false, false],
            ['administrator', 'vaqgvpochtif8gh888q6vnlch5', '192.168.1.2', 3, 8, 6, false, false, false],
            ['administrator', 'vaqgvpochtif8gh888q6vnlch5', '192.168.1.2', 2, 7, 5, false, false, false],
            ['administrator', 'vaqgvpochtif8gh888q6vnlch5', '192.168.1.2', 1, 6, 4, false, false, false],
            ['administrator', 'vaqgvpochtif8gh888q6vnlch5', '192.168.1.2', 0, 5, 3, true, false, false],
            ['administrator', 'vaqgvpochtif8gh888q6vnlch5', '192.168.1.2', 0, 4, 2, true, false, false],
            ['poweruser', 'vaqgvpochtif8gh888q6vnlch5', '192.168.1.2', 4, 3, 1, false, false, false],
            ['poweruser', 'vaqgvpochtif8gh888q6vnlch5', '192.168.1.2', 3, 2, 0, false, false, true],
            ['poweruser', 'vaqgvpochtif8gh888q6vnlch5', '192.168.1.2', 2, 1, 0, false, false, true],
            ['poweruser', 'vaqgvpochtif8gh888q6vnlch5', '192.168.1.2', 1, 0, 0, false, true, true],
            ['poweruser', 'vaqgvpochtif8gh888q6vnlch5', '192.168.1.2', 0, 0, 0, true, true, true],
            ['poweruser', 'vaqgvpochtif8gh888q6vnlch5', '192.168.1.2', 0, 0, 0, true, true, true],
            ['fooroot', '3hto06tko273jjc1se0v1aqvvn', '192.168.1.3', 4, 9, 19, false, false, false],
            ['fooroot', '3hto06tko273jjc1se0v1aqvvn', '192.168.1.3', 3, 8, 18, false, false, false],
            ['fooroot', '3hto06tko273jjc1se0v1aqvvn', '192.168.1.3', 2, 7, 17, false, false, false],
            ['fooroot', '3hto06tko273jjc1se0v1aqvvn', '192.168.1.3', 1, 6, 16, false, false, false],
        ];
    }

    /**
     * Test login.
     *
     * @dataProvider wrongCredentialProvider
     *
     * @param string $user      User name.
     * @param string $sessionId Session id.
     * @param string $ipAddress Ip address.
     * @param int    $awsU      Attempts with same user.
     * @param int    $awsS      Attempts with same session id.
     * @param int    $awsI      Attempts with same ip
     * @param bool   $banU      Is user banned?.
     * @param bool   $banS      Is session id banned?.
     * @param bool   $banI      Is ip banned?.
     *
     * @return void
     */
    public function testLogin(string $user, string $sessionId, string $ipAddress, int $awsU, int $awsS, int $awsI, bool $banU, bool $banS, bool $banI): void
    {
        $this->assertFalse(self::$enhancedAuthentication->login($user, 'passwor', $user, '$2y$11$4IAn6SRaB0osPz8afZC5D.CmTrBGxnb5FQEygPjDirK9SWE/u8YuO', 1));

        $this->storeLoginAttempt($user, $sessionId, $ipAddress);

        //Access with user
        $this->assertEquals($awsU, self::$enhancedAuthentication->getAttemptsLeftWithSameUser($user));
        //Access with session
        $this->assertEquals($awsS, self::$enhancedAuthentication->getAttemptsLeftWithSameSession($sessionId));
        //Access with ip
        $this->assertEquals($awsI, self::$enhancedAuthentication->getAttemptsLeftWithSameIp($ipAddress));

        //User Banned
        $this->assertEquals($banU, self::$enhancedAuthentication->isUserBanned($user));
        //Session Banned
        $this->assertEquals($banS, self::$enhancedAuthentication->isSessionBanned($sessionId));
        //Ip Banned
        $this->assertEquals($banI, self::$enhancedAuthentication->isIpBanned($ipAddress));
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
        /** @var \Linna\Authentication\LoginAttempt Login Attempt. */
        $loginAttempt = self::$enhancedAuthenticationMapper->create();
        $loginAttempt->userName = $user;
        $loginAttempt->sessionId = $sessionId;
        $loginAttempt->ipAddress = $ipAddress;
        $loginAttempt->when = \date(DATE_ATOM, \time());

        self::$enhancedAuthenticationMapper->save($loginAttempt);
    }
}
