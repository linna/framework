<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Auth\EnhancedUser;
use Linna\Auth\Password;
use Linna\Foo\Mappers\EnhancedUserMapper;
use Linna\Foo\Mappers\PermissionMapper;
use Linna\Storage\StorageFactory;
use PHPUnit\Framework\TestCase;

class EnhancedUserTest extends TestCase
{
    protected $permissionMapper;

    protected $enhancedUserMapper;

    public function setUp()
    {
        $options = [
            'dsn' => $GLOBALS['pdo_mysql_dsn'],
            'user' => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'options' => [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_PERSISTENT         => false,
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
            ]  
        ];

        $pdo = (new StorageFactory('pdo', $options))->getConnection();

        $password = new Password();

        $this->permissionMapper = new PermissionMapper($pdo);
        $this->enhancedUserMapper = new EnhancedUserMapper($pdo, $password, $this->permissionMapper);
    }

    public function testCreateEnhancedUser()
    {
        $user = $this->enhancedUserMapper->create();

        $this->assertInstanceOf(EnhancedUser::class, $user);
    }

    public function testEnhancedUserPermission()
    {
        $permission = $this->permissionMapper->fetchAll();

        $arrayPermissions = [];

        foreach ($permission as $ownPermission) {
            $arrayPermissions[] = $ownPermission->name;
        }

        $user = $this->enhancedUserMapper->create();
        $user->setPermissions($permission);

        $this->assertEquals($arrayPermissions, $user->showPermissions());
    }

    public function testEnhancedUserCan()
    {
        $permission = $this->permissionMapper->fetchAll();
        $user = $this->enhancedUserMapper->create();
        $user->setPermissions($permission);

        $this->assertEquals(true, $user->can('see users'));
        $this->assertEquals(false, $user->can('other permission'));
    }
}
