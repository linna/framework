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

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * Role Test.
 */
class RoleTest extends TestCase
{
    /** @var Role The role instance */
    protected static Role $role;

    /**
     * Set up before class.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$role = new Role(
            name:            'test_role',
            description:     'test_role_description',
            active:          1,
            created:         new DateTimeImmutable(),
            lastUpdate:      new DateTimeImmutable()
        );
    }

    /**
    * Test new role instance.
    *
     * @return void
    */
    public function testNewRoleInstance(): void
    {
        $role = self::$role;

        $this->assertInstanceOf(Role::class, $role);
        $this->assertInstanceOf(DateTimeImmutable::class, $role->created);
        $this->assertInstanceOf(DateTimeImmutable::class, $role->lastUpdate);

        //id null because not saved into persistent storage
        $this->assertSame(null, $role->id);
        $this->assertSame('test_role', $role->name);
        $this->assertSame('test_role_description', $role->description);
        $this->assertSame(1, $role->active);
    }

    /**
     * Test constructor type casting.
     *
     * @return void
     */
    /*public function testConstructorTypeCasting(): void
    {
        $role = self::$roleMapper->fetchById(1);

        $this->assertIsInt($role->getId());
        $this->assertIsInt($role->id);
        $this->assertIsInt($role->active);

        $this->assertGreaterThan(0, $role->getId());
        $this->assertGreaterThan(0, $role->id);
    }*/

    /**
     * User Role data provider.
     *
     * @return array
     */
    /*public static function userRoleProvider(): array
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
    //public function testIsUserInRole(int $roleId, int $userId, bool $result): void
    //{
    /** @var Role Role Class. */
    // $role = self::$roleMapper->fetchById($roleId);

    /** @var EnhancedUser Enhanced User Class. */
    // $user = self::$enhancedUserMapper->fetchById($userId);
    //  $this->assertEquals($result, $role->isUserInRole($user));
    //}

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
    /*public function testIsUserInRoleById(int $roleId, int $userId, bool $result): void
    {
        $role = self::$roleMapper->fetchById($roleId);

        $this->assertEquals($result, $role->isUserInRoleById($userId));
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
    /*public function testIsUserInRoleByName(int $roleId, int $userId, bool $result): void
    {

        $role = self::$roleMapper->fetchById($roleId);


        $user = self::$enhancedUserMapper->fetchById($userId);
        $this->assertEquals($result, $role->isUserInRoleByName($user->name));
    }

    /**
     * Role Permission data provider.
     *
     * @return array
     * @return void
     */
    /*public static function rolePermissionProvider(): array
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
    /*public function testRoleCan(int $roleId, int $permissionId, bool $result): void
    {

        $role = self::$roleMapper->fetchById($roleId);


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
    /*public function testRoleCanById(int $roleId, int $permissionId, bool $result): void
    {

        $role = self::$roleMapper->fetchById($roleId);

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
    /*public function testRoleCanByName(int $roleId, int $permissionId, bool $result): void
    {

        $role = self::$roleMapper->fetchById($roleId);

        $permission = self::$permissionMapper->fetchById($permissionId);
        $this->assertEquals($result, $role->canByName($permission->name));
    }*/
}
