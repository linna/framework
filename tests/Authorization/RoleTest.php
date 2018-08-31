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
use Linna\Authorization\Role;
use Linna\TestHelper\Mappers\EnhancedUserMapper;
use Linna\TestHelper\Mappers\PermissionMapper;
use Linna\TestHelper\Mappers\RoleMapper;
use Linna\Storage\StorageFactory;
use PDO;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    /**
     * @var PermissionMapper The permission mapper
     */
    protected $permissionMapper;

    /**
     * @var EnhancedUserMapper The enhanced user mapper
     */
    protected $enhancedUserMapper;

    /**
     * @var RoleMapper The role mapper
     */
    protected $roleMapper;

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

        $pdo = (new StorageFactory('pdo', $options))->get();

        $password = new Password();

        $permissionMapper = new PermissionMapper($pdo);
        $enhancedUserMapper = new EnhancedUserMapper($pdo, $password, $permissionMapper);

        $this->roleMapper = new RoleMapper($pdo, $password, $enhancedUserMapper, $permissionMapper);

        $this->permissionMapper = $permissionMapper;
        $this->enhancedUserMapper = $enhancedUserMapper;
    }

    /**
     * Test new role instance.
     */
    public function testNewRoleInstance(): void
    {
        $this->assertInstanceOf(Role::class, $this->roleMapper->create());
    }

    /**
     * Test role set and get users.
     */
    public function testRoleSetAndGetUsers(): void
    {
        $users = $this->enhancedUserMapper->fetchAll();

        /** @var Role Role Class. */
        $role = $this->roleMapper->create();
        $role->setUsers($users);

        $this->assertEquals($users, $role->getUsers());
    }

    /**
     * Test is user in role.
     */
    public function testIsUserInRole(): void
    {
        /** @var Role Role Class. */
        $role = $this->roleMapper->create();
        $role->setUsers($this->enhancedUserMapper->fetchAll());

        $this->assertTrue($role->isUserInRole('root'));
        $this->assertFalse($role->isUserInRole('foo_root'));
    }

    /**
     * Test role set and get permission.
     */
    public function testRoleSetAndGetPermission(): void
    {
        $permission = $this->permissionMapper->fetchAll();

        /** @var Role Role Class. */
        $role = $this->roleMapper->create();
        $role->setPermissions($permission);

        $this->assertEquals($permission, $role->getPermissions());
    }

    /**
     * Test role can do action.
     */
    public function testRoleCanDoAction(): void
    {
        /** @var Role Role Class. */
        $role = $this->roleMapper->create();
        $role->setPermissions($this->permissionMapper->fetchAll());

        $this->assertTrue($role->can('see users'));
        $this->assertFalse($role->can('other permission'));
    }
}
