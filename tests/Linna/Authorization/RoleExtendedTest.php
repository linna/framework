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

use Linna\Authentication\Password;
use Linna\Storage\ExtendedPDO;
use Linna\Storage\StorageFactory;
use PHPUnit\Framework\TestCase;
use Linna\TestHelper\Pdo\PdoOptionsFactory;

class RoleExtendedTest extends TestCase
{
    /** @var RoleExtendedMapper The role extended mapper */
    protected static RoleExtendedMapper $roleExtendedMapper;

    /** @var UserMapper The user mapper */
    protected static UserMapper $userMapper;

    /** @var PermissionMapper The permission mapper */
    protected static PermissionMapper $permissionMapper;

    /** @var ExtendedPDO Database connection. */
    protected static ExtendedPDO $pdo;

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

        $pdo = (new StorageFactory('pdo', PdoOptionsFactory::getOptions()))->get();

        $password = new Password();

        $permissionMapper = new PermissionMapper($pdo);
        $userMapper = new UserMapper($pdo, $password);

        $roleExtendedMapper = new RoleExtendedMapper($pdo, $permissionMapper, $userMapper);

        self::$pdo = $pdo;
        self::$userMapper = $userMapper;
        self::$permissionMapper = $permissionMapper;
        self::$roleExtendedMapper = $roleExtendedMapper;
    }

    /**
     * Test new role instance.
     *
     * @return void
     */
    public function testNewRoleInstance(): void
    {
        $this->assertInstanceOf(Role::class, self::$roleExtendedMapper->create());
    }

    /**
     * Test constructor type casting.
     *
     * @return void
     */
    public function testConstructorTypeCasting(): void
    {
        $role = self::$roleExtendedMapper->fetchById(1);

        $this->assertIsInt($role->getId());
        $this->assertIsInt($role->id);
        $this->assertIsInt($role->active);

        $this->assertGreaterThan(0, $role->getId());
        $this->assertGreaterThan(0, $role->id);
    }

    /**
     * User Role data provider.
     *
     * @return array
     */
    public static function userRoleProvider(): array
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
     * @param int  $roleId
     * @param int  $userId
     * @param bool $result
     *
     * @return void
     */
    public function testIsUserInRole(int $roleId, int $userId, bool $result): void
    {
        /** @var RoleExtended Role Class. */
        $role = self::$roleExtendedMapper->fetchById($roleId);

        /** @var UserExtended Enhanced User Class. */
        $user = self::$userMapper->fetchById($userId);
        $this->assertEquals($result, $role->hasUser($user));
    }

    /**
     * Test is user in role by id.
     *
     * @dataProvider userRoleProvider
     *
     * @param int  $roleId
     * @param int  $userId
     * @param bool $result
     *
     * @return void
     */
    public function testIsUserInRoleById(int $roleId, int $userId, bool $result): void
    {
        /** @var RoleExtended Role Class. */
        $role = self::$roleExtendedMapper->fetchById($roleId);

        $this->assertEquals($result, $role->hasUserById($userId));
    }

    /**
     * Test is user in role by name.
     *
     * @dataProvider userRoleProvider
     *
     * @param int  $roleId
     * @param int  $userId
     * @param bool $result
     *
     * @return void
     */
    public function testIsUserInRoleByName(int $roleId, int $userId, bool $result): void
    {
        /** @var RoleExtended Role Class. */
        $role = self::$roleExtendedMapper->fetchById($roleId);

        /** @var UserExtended Enhanced User Class. */
        $user = self::$userMapper->fetchById($userId);
        $this->assertEquals($result, $role->hasUserByName($user->name));
    }

    /**
     * Role Permission data provider.
     *
     * @return array
     * @return void
     */
    public static function rolePermissionProvider(): array
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
     * @param int  $roleId
     * @param int  $permissionId
     * @param bool $result
     *
     * @return void
     */
    public function testRoleCan(int $roleId, int $permissionId, bool $result): void
    {
        /** @var RoleExtended Role Class. */
        $role = self::$roleExtendedMapper->fetchById($roleId);

        /** @var Permission Permission Class. */
        $permission = self::$permissionMapper->fetchById($permissionId);
        $this->assertEquals($result, $role->can($permission));
    }

    /**
     * Test role can by id.
     *
     * @dataProvider rolePermissionProvider
     *
     * @param int  $roleId
     * @param int  $permissionId
     * @param bool $result
     *
     * @return void
     */
    public function testRoleCanById(int $roleId, int $permissionId, bool $result): void
    {
        /** @var RoleExtended Role Class. */
        $role = self::$roleExtendedMapper->fetchById($roleId);

        $this->assertEquals($result, $role->canById($permissionId));
    }

    /**
     * Test role can by name.
     *
     * @dataProvider rolePermissionProvider
     *
     * @param int  $roleId
     * @param int  $permissionId
     * @param bool $result
     *
     * @return void
     */
    public function testRoleCanByName(int $roleId, int $permissionId, bool $result): void
    {
        /** @var RoleExtended Role Class. */
        $role = self::$roleExtendedMapper->fetchById($roleId);

        /** @var Permission Permission Class. */
        $permission = self::$permissionMapper->fetchById($permissionId);
        $this->assertEquals($result, $role->canByName($permission->name));
    }
}
