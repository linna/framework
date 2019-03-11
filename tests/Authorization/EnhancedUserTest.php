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

use Linna\Authentication\Password;
use Linna\Authentication\UserMapper;
use Linna\Authorization\EnhancedUser;
use Linna\Authorization\EnhancedUserMapper;
use Linna\Authorization\PermissionMapper;
use Linna\Authorization\RoleMapper;
use Linna\Authorization\RoleToUserMapper;
use Linna\Storage\StorageFactory;
use PDO;
use PHPUnit\Framework\TestCase;

/**
 * Enhanced User Test.
 */
class EnhancedUserTest extends TestCase
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
     * Test create new enhanced user instance.
     *
     * @return void
     */
    public function testNewEnhancedUserInstance(): void
    {
        $this->assertInstanceOf(EnhancedUser::class, self::$enhancedUserMapper->create());
    }

    /**
     * Test constructor type casting.
     *
     * @return void
     */
    public function testConstructorTypeCasting(): void
    {
        $user = self::$enhancedUserMapper->fetchByName('root');

        $this->assertIsInt($user->getId());
        $this->assertIsInt($user->rId);
        $this->assertIsInt($user->active);

        $this->assertGreaterThan(0, $user->getId());
        $this->assertGreaterThan(0, $user->rId);
    }

    /**
     * User Permission data provider.
     *
     * @return array
     */
    public function userPermissionProvider(): array
    {
        return [
            [4, 1, true], //permission inherited from role
            [4, 2, false],
            [4, 3, false],
            [4, 4, false],
            [4, 5, true],
            [4, 6, true],
            [5, 1, true], //permission inherited from role
            [5, 2, false],
            [5, 3, true],
            [5, 4, true],
            [5, 5, true],
            [5, 6, true]
        ];
    }

    /**
     * Test user can.
     *
     * @dataProvider userPermissionProvider
     *
     * @return void
     */
    public function testUserCan($userId, $permissionId, $result): void
    {
        /** @var EnhancedUserMapper Enhanced user mapper Class. */
        $user = self::$enhancedUserMapper->fetchById($userId);

        /** @var Permission Permission Class. */
        $permission = self::$permissionMapper->fetchById($permissionId);
        $this->assertEquals($result, $user->can($permission));
    }

    /**
     * Test user can by id.
     *
     * @dataProvider userPermissionProvider
     *
     * @return void
     */
    public function testUserCanById($userId, $permissionId, $result): void
    {
        /** @var EnhancedUserMapper Enhanced user mapper Class. */
        $user = self::$enhancedUserMapper->fetchById($userId);

        $this->assertEquals($result, $user->canById($permissionId));
    }

    /**
     * Test user can by name.
     *
     * @dataProvider userPermissionProvider
     *
     * @return void
     */
    public function testUserCanByName($userId, $permissionId, $result): void
    {
        /** @var EnhancedUserMapper Enhanced user mapper Class. */
        $user = self::$enhancedUserMapper->fetchById($userId);

        /** @var Permission Permission Class. */
        $permission = self::$permissionMapper->fetchById($permissionId);
        $this->assertEquals($result, $user->canByName($permission->name));
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
     * Test user has role.
     *
     * @dataProvider userRoleProvider
     *
     * @return void
     */
    public function testUserHasRole($roleId, $userId, $result): void
    {
        $user = self::$enhancedUserMapper->fetchById($userId);

        $role = self::$roleMapper->fetchById($roleId);
        $this->assertEquals($result, $user->hasRole($role));
    }

    /**
     * Test user has role by id.
     *
     * @dataProvider userRoleProvider
     *
     * @return void
     */
    public function testUserHasRoleById($roleId, $userId, $result): void
    {
        $user = self::$enhancedUserMapper->fetchById($userId);

        $this->assertEquals($result, $user->hasRoleById($roleId));
    }

    /**
     * Test user has role by name.
     *
     * @dataProvider userRoleProvider
     *
     * @return void
     */
    public function testUserHasRoleByName($roleId, $userId, $result): void
    {
        $user = self::$enhancedUserMapper->fetchById($userId);

        $role = self::$roleMapper->fetchById($roleId);
        $this->assertEquals($result, $user->hasRoleByName($role->name));
    }
}
