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

use Linna\Storage\ExtendedPDO;
use Linna\Authentication\Password;
use Linna\Authentication\UserMapper;
use Linna\Authorization\EnhancedUser;
use Linna\Authorization\EnhancedUserMapper;
use Linna\Authorization\PermissionMapper;
use Linna\Authorization\Role;
use Linna\Authorization\RoleMapper;
use Linna\Authorization\RoleToUserMapper;
use Linna\Storage\StorageFactory;
use PDO;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    /**
     * @var PermissionMapper The permission mapper
     */
    protected static $permissionMapper;

    /**
     * @var EnhancedUserMapper The enhanced user mapper
     */
    protected static $enhancedUserMapper;

    /**
     * @var RoleMapper The role mapper
     */
    protected static $roleMapper;

    /**
     * @var ExtendedPDO Database connection.
     */
    protected static $pdo;

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

        $password = new Password();

        $permissionMapper = new PermissionMapper($pdo);
        $userMapper = new UserMapper($pdo, $password);
        $roleToUserMapper = new RoleToUserMapper($pdo, $password);
        $enhancedUserMapper = new EnhancedUserMapper($pdo, $password, $permissionMapper, $roleToUserMapper);

        self::$pdo = $pdo;
        self::$roleMapper = new RoleMapper($pdo, $permissionMapper, $userMapper, $roleToUserMapper);
        self::$permissionMapper = $permissionMapper;
        self::$enhancedUserMapper = $enhancedUserMapper;
    }

    /**
     * Tear down after class.
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        self::$pdo = null;
        self::$roleMapper = null;
        self::$permissionMapper = null;
        self::$enhancedUserMapper = null;
    }

    /**
     * Test new role instance.
     *
     * @return void
     */
    public function testNewRoleInstance(): void
    {
        $this->assertInstanceOf(Role::class, self::$roleMapper->create());
    }

    /**
     * Test constructor type casting.
     *
     * @return void
     */
    public function testConstructorTypeCasting(): void
    {
        $role = self::$roleMapper->fetchById(1);

        $this->assertIsInt($role->getId());
        $this->assertIsInt($role->rId);
        $this->assertIsInt($role->active);

        $this->assertGreaterThan(0, $role->getId());
        $this->assertGreaterThan(0, $role->rId);
    }

    /**
     * User Role data provider.
     *
     * @return array
     */
    public function userRoleProvider(): array
    {
        return [
            [1, 1, true],
            [1, 2, false],
            [1, 3, false],
            [1, 4, false],
            [1, 5, false],
            [1, 6, false],
            [1, 7, false],
            [2, 1, false],
            [2, 2, true],
            [2, 3, true],
            [2, 4, false],
            [2, 5, false],
            [2, 6, false],
            [2, 7, false],
            [3, 1, false],
            [3, 2, false],
            [3, 3, false],
            [3, 4, true],
            [3, 5, true],
            [3, 6, true],
            [3, 7, true],
        ];
    }

    /**
     * Test is user in role.
     *
     * @dataProvider userRoleProvider
     *
     * @return void
     */
    public function testIsUserInRole($roleId, $userId, $result): void
    {
        /** @var Role Role Class. */
        $role = self::$roleMapper->fetchById($roleId);

        /** @var EnhancedUser Enhanced User Class. */
        $user = self::$enhancedUserMapper->fetchById($userId);
        $this->assertEquals($result, $role->isUserInRole($user));
    }

    /**
     * Test is user in role by id.
     *
     * @dataProvider userRoleProvider
     *
     * @return void
     */
    public function testIsUserInRoleById($roleId, $userId, $result): void
    {
        /** @var Role Role Class. */
        $role = self::$roleMapper->fetchById($roleId);

        $this->assertEquals($result, $role->isUserInRoleById($userId));
    }

    /**
     * Test is user in role by name.
     *
     * @dataProvider userRoleProvider
     *
     * @return void
     */
    public function testIsUserInRoleByName($roleId, $userId, $result): void
    {
        /** @var Role Role Class. */
        $role = self::$roleMapper->fetchById($roleId);

        /** @var EnhancedUser Enhanced User Class. */
        $user = self::$enhancedUserMapper->fetchById($userId);
        $this->assertEquals($result, $role->isUserInRoleByName($user->name));
    }

    /**
     * Role Permission data provider.
     *
     * @return array
     *
     * @return void
     */
    public function rolePermissionProvider(): array
    {
        return [
            [1, 1, true],
            [1, 2, true],
            [1, 3, true],
            [1, 4, true],
            [1, 5, true],
            [1, 6, true],
            [2, 1, true],
            [2, 2, true],
            [2, 3, false],
            [2, 4, false],
            [2, 5, true],
            [2, 6, true],
            [3, 1, true],
            [3, 2, false],
            [3, 3, false],
            [3, 4, false],
            [3, 5, false],
            [3, 6, false],
        ];
    }

    /**
     * Test role can.
     *
     * @dataProvider rolePermissionProvider
     *
     * @return void
     */
    public function testRoleCan($roleId, $permissionId, $result): void
    {
        /** @var Role Role Class. */
        $role = self::$roleMapper->fetchById($roleId);

        /** @var Permission Permission Class. */
        $permission = self::$permissionMapper->fetchById($permissionId);
        $this->assertEquals($result, $role->can($permission));
    }

    /**
     * Test role can by id.
     *
     * @dataProvider rolePermissionProvider
     *
     * @return void
     */
    public function testRoleCanById($roleId, $permissionId, $result): void
    {
        /** @var Role Role Class. */
        $role = self::$roleMapper->fetchById($roleId);

        $this->assertEquals($result, $role->canById($permissionId));
    }

    /**
     * Test role can by name.
     *
     * @dataProvider rolePermissionProvider
     *
     * @return void
     */
    public function testRoleCanByName($roleId, $permissionId, $result): void
    {
        /** @var Role Role Class. */
        $role = self::$roleMapper->fetchById($roleId);

        /** @var Permission Permission Class. */
        $permission = self::$permissionMapper->fetchById($permissionId);
        $this->assertEquals($result, $role->canByName($permission->name));
    }
}
