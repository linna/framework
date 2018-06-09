<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Authentication\EnhancedAuthenticate;
use Linna\TestHelper\Mappers\EnhancedAuthenticateMapper;
use Linna\Authentication\Password;
use Linna\Session\Session;
use Linna\Storage\StorageFactory;
use PHPUnit\Framework\TestCase;

/**
 * Enhanced Authenticate Test.
 */
class EnhancedAuthenticateTest extends TestCase
{
    /**
     * @var Session The session class.
     */
    protected $session;

    /**
     * @var Password The password class.
     */
    protected $password;

    /**
     * @var EnhancedAuthenticate The enhanced authenticate class
     */
    protected $enhancedAuthenticate;

    
    /**
     * @var EnhancedAuthenticateMapper The enhanced authenticate mapper class
     */
    protected $eAMapper;
    
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
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_PERSISTENT         => false,
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
            ],
        ];

        $session = new Session();
        $password = new Password();

        $this->password = $password;
        $this->session = $session;
        $this->eAMapper = new EnhancedAuthenticateMapper((new StorageFactory('pdo', $options))->get());
        $this->enhancedAuthenticate = new EnhancedAuthenticate($session, $password, $this->eAMapper);
    }

    /**
     * Wrong arguments router class provider.
     *
     * @return array
     */
    public function wrongCredentialProvider(): array
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
     */
    public function testLogin(string $user, string $sessionId, string $ipAddress, int $awsU, int $awsS, int $awsI, bool $banU, bool $banS, bool $banI): void
    {
        $this->assertFalse($this->enhancedAuthenticate->login($user, 'passwor', $user, '$2y$11$4IAn6SRaB0osPz8afZC5D.CmTrBGxnb5FQEygPjDirK9SWE/u8YuO', 1));

        $this->storeLoginAttempt($user, $sessionId, $ipAddress);

        //Access with user
        $this->assertEquals($awsU, $this->enhancedAuthenticate->getAttemptsLeftWithSameUser($user));
        //Access with session
        $this->assertEquals($awsS, $this->enhancedAuthenticate->getAttemptsLeftWithSameSession($sessionId));
        //Access with ip
        $this->assertEquals($awsI, $this->enhancedAuthenticate->getAttemptsLeftWithSameIp($ipAddress));

        //User Banned
        $this->assertEquals($banU, $this->enhancedAuthenticate->isUserBanned($user));
        //Session Banned
        $this->assertEquals($banS, $this->enhancedAuthenticate->isSessionBanned($sessionId));
        //Ip Banned
        $this->assertEquals($banI, $this->enhancedAuthenticate->isIpBanned($ipAddress));
    }

    /**
     * Set up before class.
     */
    public static function setUpBeforeClass(): void
    {
        self::loginClean();
    }

    /**
     * Tear down After Class.
     */
    public static function tearDownAfterClass(): void
    {
        self::loginClean();
    }

    /**
     * Remove record from login_attemp table.
     */
    protected static function loginClean(): void
    {
        $options = [
            'dsn'      => $GLOBALS['pdo_mysql_dsn'],
            'user'     => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'options'  => [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_PERSISTENT         => false,
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
            ],
        ];

        (new EnhancedAuthenticateMapper((new StorageFactory('pdo', $options))->get()))->deleteOldLoginAttempts(-86400);
    }

    /**
     * Store login attempts.
     *
     * @param string $user
     * @param string $sessionId
     * @param string $ipAddress
     */
    protected function storeLoginAttempt(string &$user, string &$sessionId, string &$ipAddress): void
    {
        /** @var \Linna\Authentication\LoginAttempt Login Attempt. */
        $loginAttempt = $this->eAMapper->create();
        $loginAttempt->userName = $user;
        $loginAttempt->sessionId = $sessionId;
        $loginAttempt->ipAddress = $ipAddress;
        $loginAttempt->when = date('YmdHis', time());

        $this->eAMapper->save($loginAttempt);
    }
}
