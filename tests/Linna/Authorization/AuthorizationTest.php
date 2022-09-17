<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Authorization;

use Linna\Authentication\Authentication;
use Linna\Authentication\Password;
//use Linna\Authorization\Authorization;
//use Linna\Authorization\PermissionMapper;
use Linna\DataMapper\NullDomainObject;
use Linna\Session\Session;
use Linna\Storage\StorageFactory;
use PDO;
use PHPUnit\Framework\TestCase;

/**
 * Authorization Test.
 */
class AuthorizationTest extends TestCase
{
    /** @var Session The session class instance. */
    protected static Session $session;

    /** @var Password The password class instance. */
    protected static Password $password;

    /** @var Authentication The authentication class instance. */
    protected static Authentication $authentication;

    /** @var Authorization The authorization class instance. */
    protected static Authorization $authorization;

    /** @var PermissionMapper The permission mapper class instance. */
    protected static PermissionMapper $permissionMapper;

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

        $session = new Session();
        $password = new Password();
        $authentication = new Authentication($session, $password);
        $permissionMapper = new PermissionMapper((new StorageFactory('pdo', $options))->get());

        self::$password = $password;
        self::$session = $session;
        self::$authentication = $authentication;
        self::$permissionMapper = $permissionMapper;

        self::$authorization = new Authorization($authentication, $permissionMapper);
    }

    /**
     * Tear down after class.
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        //self::$password = null;
        //self::$session = null;
        //self::$authentication = null;
        //self::$permissionMapper = null;
    }

    /**
     * Test create new authorization instance.
     */
    public function testNewAuthorizationInstance(): void
    {
        $this->assertInstanceOf(Authorization::class, self::$authorization);
    }

    /**
     * Test can do an action without login.
     */
    public function testCanDoActionWithoutLogin(): void
    {
        $permission = self::$permissionMapper->fetchById(1);

        $this->assertFalse(self::$authorization->can(new NullDomainObject()));
        $this->assertFalse(self::$authorization->can($permission));
        $this->assertFalse(self::$authorization->can(1));
        $this->assertFalse(self::$authorization->can('see users'));
    }

    /**
     * Test can do an action with invalid permission.
     *
     * @return void
     */
    public function testCanDoActionWithInvalidPermission(): void
    {
        $this->assertFalse(self::$authorization->can(new Password()));
    }

    /**
     * Test can do an action with login.
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testCanDoActionWithLogin(): void
    {
        self::$session->start();

        $authentication = new Authentication(self::$session, self::$password);

        //attemp login
        $this->assertTrue($authentication->login(
            'root',
            'password',
            'root',
            self::$password->hash('password'),
            1
        ));

        //pass as first argument new instance because phpunit try to serialize pdo.????? I don't know where.
        $authorization = new Authorization(new Authentication(self::$session, self::$password), self::$permissionMapper);
        $permission = self::$permissionMapper->fetchById(1);

        $this->assertFalse($authorization->can(new NullDomainObject()));

        $this->assertTrue($authentication->isLogged());
        $this->assertTrue($authorization->can($permission));
        $this->assertTrue($authorization->can(1));
        $this->assertTrue($authorization->can('see users'));

        self::$session->destroy();
    }
}
