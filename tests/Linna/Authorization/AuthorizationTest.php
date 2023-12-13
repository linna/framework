<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Authorization;

use Linna\Authentication\Authentication;
use Linna\Authentication\Password;
use Linna\DataMapper\NullDomainObject;
use Linna\Session\Session;
use Linna\Storage\StorageFactory;
use PHPUnit\Framework\TestCase;
use Linna\TestHelper\Pdo\PdoOptionsFactory;

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

    /** @var PermissionExtendedMapper The permission mapper class instance. */
    protected static PermissionExtendedMapper $permissionMapper;

    /**
     * Set up before class.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        $pdo = (new StorageFactory('pdo', PdoOptionsFactory::getOptions()))->get();

        $session = new Session();
        $password = new Password();
        $authentication = new Authentication($session, $password);

        $userMapper =  new UserMapper($pdo, $password);
        $roleMapper = new RoleMapper($pdo);

        $permissionMapper = new PermissionExtendedMapper($pdo, $roleMapper, $userMapper);

        self::$password = $password;
        self::$session = $session;
        self::$authentication = $authentication;
        self::$permissionMapper = $permissionMapper;

        self::$authorization = new Authorization($authentication, $permissionMapper);
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
