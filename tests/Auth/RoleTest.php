<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Auth\Password;
use Linna\Auth\Role;
use Linna\Foo\Mappers\EnhancedUserMapper;
use Linna\Foo\Mappers\PermissionMapper;
use Linna\Foo\Mappers\RoleMapper;
use Linna\Storage\StorageFactory;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    protected $permissionMapper;

    protected $enhancedUserMapper;

    protected $roleMapper;

    public function setUp()
    {
        $options = [
            'dsn'      => $GLOBALS['pdo_mysql_dsn'],
            'user'     => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'options'  => [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING],
        ];

        $pdo = (new StorageFactory())->createConnection('mysqlpdo', $options);

        $password = new Password();

        $permissionMapper = new PermissionMapper($pdo);
        $enhancedUserMapper = new EnhancedUserMapper($pdo, $password, $permissionMapper);
        $this->roleMapper = new RoleMapper($pdo, $password, $enhancedUserMapper, $permissionMapper);

        $this->permissionMapper = $permissionMapper;
        $this->enhancedUserMapper = $enhancedUserMapper;
    }

    public function testCreateRole()
    {
        $role = $this->roleMapper->create();

        $this->assertInstanceOf(Role::class, $role);
    }

    public function testRoleUsers()
    {
        $users = $this->enhancedUserMapper->fetchAll();

        $arrayUsers = [];

        foreach ($users as $ownUser) {
            $arrayUsers[] = $ownUser->name;
        }

        $role = $this->roleMapper->create();
        $role->setUsers($users);

        $this->assertEquals($arrayUsers, $role->showUsers());
    }

    public function testIsUserInRole()
    {
        $user = $this->enhancedUserMapper->fetchAll();

        $role = $this->roleMapper->create();
        $role->setUsers($user);

        $this->assertEquals(true, $role->isUserInRole('root'));
        $this->assertEquals(false, $role->isUserInRole('foo_root'));
    }

    public function testRolePermission()
    {
        $permission = $this->permissionMapper->fetchAll();

        $arrayPermissions = [];

        foreach ($permission as $ownPermission) {
            $arrayPermissions[] = $ownPermission->name;
        }

        $role = $this->roleMapper->create();
        $role->setPermissions($permission);

        $this->assertEquals($arrayPermissions, $role->showPermissions());
    }

    public function testRoleCan()
    {
        $permission = $this->permissionMapper->fetchAll();
        $role = $this->roleMapper->create();
        $role->setPermissions($permission);

        $this->assertEquals(true, $role->can('see users'));
        $this->assertEquals(false, $role->can('other permission'));
    }
}
