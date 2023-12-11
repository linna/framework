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

class PermissionExtendedTest extends TestCase
{
    /** @var PermissionExtendedMapper The permission mapper */
    protected static PermissionExtendedMapper $permissionExtendedMapper;

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

        $roleMapper = new RoleMapper($pdo);
        $userMapper = new UserMapper($pdo, $password);
        $permissionExtendedMapper = new PermissionExtendedMapper($pdo, $roleMapper, $userMapper);

        //declared in trait
        self::$pdo = $pdo;
        self::$permissionExtendedMapper = $permissionExtendedMapper;
    }

    /**
     * Test new role instance.
     *
     * @return void
     */
    public function testNewRoleInstance(): void
    {
        $this->assertInstanceOf(Permission::class, self::$permissionExtendedMapper->create());
    }

    /**
     * Test constructor type casting.
     *
     * @return void
     */
    public function testConstructorTypeCasting(): void
    {
        $permission = self::$permissionExtendedMapper->fetchById(1);

        $this->assertIsInt($permission->getId());
        $this->assertIsInt($permission->id);
        $this->assertIsInt($permission->inherited);

        $this->assertGreaterThan(0, $permission->getId());
        $this->assertGreaterThan(0, $permission->id);
    }
}
