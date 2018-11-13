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

use Linna\Authentication\Authentication;
use Linna\Authentication\Password;
use Linna\Authorization\Authorization;
use Linna\Authorization\PermissionMapper;
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
    /**
     * @var Session The session class instance.
     */
    protected $session;

    /**
     * @var Password The password class instance.
     */
    protected $password;

    /**
     * @var Authentication The authentication class instance.
     */
    protected $authentication;

    /**
     * @var Authorization The authorization class instance.
     */
    protected $authorization;

    /**
     * @var PermissionMapper The permission mapper class instance.
     */
    protected $permissionMapper;

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

        $session = new Session();
        $password = new Password();
        $authentication = new Authentication($session, $password);
        $permissionMapper = new PermissionMapper((new StorageFactory('pdo', $options))->get());

        $this->password = $password;
        $this->session = $session;
        $this->authentication = $authentication;
        $this->permissionMapper = $permissionMapper;

        $this->authorization = new Authorization($authentication, $permissionMapper);
    }

    /**
     * Tear Down.
     */
    public function tearDown()
    {
        unset($this->password, $this->session, $this->authentication, $this->permissionMapper);
    }

    /**
     * Test create new authorization instance.
     */
    public function testNewAuthorizationInstance(): void
    {
        $this->assertInstanceOf(Authorization::class, $this->authorization);
    }

    /**
     * Test can do an action without login.
     */
    public function testCanDoActionWithoutLogin(): void
    {
        $permission = $this->permissionMapper->fetchById(1);

        $this->assertFalse($this->authorization->can(new NullDomainObject));
        $this->assertFalse($this->authorization->can($permission));
        $this->assertFalse($this->authorization->can(1));
        $this->assertFalse($this->authorization->can('see users'));
    }

    /**
     * Test can do an action with invalid permission.
     */
    public function testCanDoActionWithInvalidPermission(): void
    {
        $this->assertFalse($this->authorization->can(new Password()));
    }

    /**
     * Test can do an action with login.
     *
     * @runInSeparateProcess
     */
    public function testCanDoActionWithLogin(): void
    {
        $this->session->start();

        $authentication = new Authentication($this->session, $this->password);

        //attemp login
        $this->assertTrue($authentication->login(
            'root',
            'password',
            'root',
            $this->password->hash('password'),
            1
        ));

        //pass as first argument new instance because phpunit try to serialize pdo.????? I don't know where.
        $authorization = new Authorization(new Authentication($this->session, $this->password), $this->permissionMapper);
        $permission = $this->permissionMapper->fetchById(1);

        $this->assertFalse($authorization->can(new NullDomainObject));

        $this->assertTrue($authentication->isLogged());
        $this->assertTrue($authorization->can($permission));
        $this->assertTrue($authorization->can(1));
        $this->assertTrue($authorization->can('see users'));

        $this->session->destroy();
    }
}
