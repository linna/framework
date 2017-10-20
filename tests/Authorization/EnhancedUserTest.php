<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Authentication\Password;
use Linna\Authorization\EnhancedUser;
use Linna\Foo\Mappers\EnhancedUserMapper;
use Linna\Foo\Mappers\PermissionMapper;
use Linna\Storage\StorageFactory;
use PHPUnit\Framework\TestCase;

/**
 * Enhanced User Test.
 */
class EnhancedUserTest extends TestCase
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
     * Setup.
     */
    public function setUp()
    {
        $options = [
            'dsn'      => $GLOBALS['pdo_mysql_dsn'],
            'user'     => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'options'  => [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_PERSISTENT         => false,
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
            ],
        ];

        $pdo = (new StorageFactory('pdo', $options))->get();

        $this->permissionMapper = new PermissionMapper($pdo);
        $this->enhancedUserMapper = new EnhancedUserMapper($pdo, new Password(), $this->permissionMapper);
    }

    /**
     * Test create new enhanced user instance.
     */
    public function testNewEnhancedUserInstance()
    {
        $this->assertInstanceOf(EnhancedUser::class, $this->enhancedUserMapper->create());
    }

    /**
     * Test enhanced user set and get permission.
     */
    public function testEnhancedUserSetAndGetPermission()
    {
        $permission = $this->permissionMapper->fetchAll();

        $user = $this->enhancedUserMapper->create();
        $user->setPermissions($permission);

        $this->assertEquals($permission, $user->getPermissions());
    }

    /**
     * Test enanched user can do action
     */
    public function testEnhancedUserCanDoAction()
    {
        $user = $this->enhancedUserMapper->create();
        
        $user->setPermissions($this->permissionMapper->fetchAll());

        $this->assertTrue($user->can('see users'));
        $this->assertFalse($user->can('other permission'));
    }
}
