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
use Linna\Authentication\UserMapper;
use Linna\Storage\ExtendedPDO;
use Linna\Storage\StorageFactory;
use PHPUnit\Framework\TestCase;
use Linna\TestHelper\Pdo\PdoOptionsFactory;

/**
 * Enhanced User Test.
 */
class UserExtendedTest extends TestCase
{
    /** @var UserExtendedMapper The enhanced user mapper */
    protected static UserExtendedMapper $userExtendedMapper;

    /** @var PermissionMapper The permission mapper */
    protected static PermissionMapper $permissionMapper;

    /** @var RoleMapper The role mapper */
    protected static RoleMapper $roleMapper;

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
        $roleMapper = new RoleMapper($pdo);
        $userExtendedMapper = new UserExtendedMapper($pdo, $password, $permissionMapper, $roleMapper);

        self::$pdo = $pdo;
        self::$roleMapper = $roleMapper;
        self::$permissionMapper = $permissionMapper;
        self::$userExtendedMapper = $userExtendedMapper;
    }

    /**
     * Tear down after class.
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        //self::$pdo = null;
        //self::$roleMapper = null;
        //self::$permissionMapper = null;
        //self::$UserExtendedMapper = null;
    }

    /**
     * Test create new enhanced user instance.
     *
     * @return void
     */
    public function testNewUserExtendedInstance(): void
    {
        $this->assertInstanceOf(UserExtended::class, self::$userExtendedMapper->create());
    }

    /**
     * Test constructor type casting.
     *
     * @return void
     */
    public function testConstructorTypeCasting(): void
    {
        $user = self::$userExtendedMapper->fetchByName('root');

        $this->assertIsInt($user->getId());
        $this->assertIsInt($user->id);
        $this->assertIsInt($user->active);

        $this->assertGreaterThan(0, $user->getId());
        $this->assertGreaterThan(0, $user->id);
    }

    /**
     * User Permission data provider.
     *
     * @return array
     */
    public static function userPermissionProvider(): array
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
     * @param int  $userId
     * @param int  $permissionId
     * @param bool $result
     *
     * @return void
     */
    public function testUserCan(int $userId, int $permissionId, bool $result): void
    {
        /** @var UserExtended UserExtended Class. */
        $user = self::$userExtendedMapper->fetchById($userId);

        /** @var Permission Permission Class. */
        $permission = self::$permissionMapper->fetchById($permissionId);
        $this->assertEquals($result, $user->can($permission));
    }

    /**
     * Test user can by id.
     *
     * @dataProvider userPermissionProvider
     *
     * @param int  $userId
     * @param int  $permissionId
     * @param bool $result
     *
     * @return void
     */
    public function testUserCanById(int $userId, int $permissionId, bool $result): void
    {
        /** @var UserExtended UserExtended Class. */
        $user = self::$userExtendedMapper->fetchById($userId);

        $this->assertEquals($result, $user->canById($permissionId));
    }

    /**
     * Test user can by name.
     *
     * @dataProvider userPermissionProvider
     *
     * @param int  $userId
     * @param int  $permissionId
     * @param bool $result
     *
     * @return void
     */
    public function testUserCanByName(int $userId, int $permissionId, bool $result): void
    {
        /** @var UserExtended UserExtended Class. */
        $user = self::$userExtendedMapper->fetchById($userId);

        /** @var Permission Permission Class. */
        $permission = self::$permissionMapper->fetchById($permissionId);
        $this->assertEquals($result, $user->canByName($permission->name));
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
     * Test user has role.
     *
     * @dataProvider userRoleProvider
     *
     * @param int  $roleId
     * @param int  $userId
     * @param bool $result
     *
     * @return void
     */
    public function testUserHasRole(int $roleId, int $userId, bool $result): void
    {
        /** @var UserExtended UserExtended Class. */
        $user = self::$userExtendedMapper->fetchById($userId);

        $role = self::$roleMapper->fetchById($roleId);
        $this->assertEquals($result, $user->hasRole($role));
    }

    /**
     * Test user has role by id.
     *
     * @dataProvider userRoleProvider
     *
     * @param int  $roleId
     * @param int  $userId
     * @param bool $result
     *
     * @return void
     */
    public function testUserHasRoleById(int $roleId, int $userId, bool $result): void
    {
        /** @var UserExtended UserExtended Class. */
        $user = self::$userExtendedMapper->fetchById($userId);

        $this->assertEquals($result, $user->hasRoleById($roleId));
    }

    /**
     * Test user has role by name.
     *
     * @dataProvider userRoleProvider
     *
     * @param int  $roleId
     * @param int  $userId
     * @param bool $result
     *
     * @return void
     */
    public function testUserHasRoleByName(int $roleId, int $userId, bool $result): void
    {
        /** @var UserExtended UserExtended Class. */
        $user = self::$userExtendedMapper->fetchById($userId);

        $role = self::$roleMapper->fetchById($roleId);
        $this->assertEquals($result, $user->hasRoleByName($role->name));
    }
}
