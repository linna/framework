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

use Linna\Authorization\Permission;
use Linna\Authorization\PermissionMapper;
use Linna\Storage\ExtendedPDO;
use Linna\Storage\StorageFactory;
use PDO;
use PHPUnit\Framework\TestCase;

class PermissionTest extends TestCase
{
    /**
     * @var PermissionMapper The permission mapper
     */
    protected static PermissionMapper $permissionMapper;

    /**
     * @var ExtendedPDO Database connection.
     */
    protected static ExtendedPDO $pdo;

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

        self::$pdo = $pdo;
        self::$permissionMapper = new PermissionMapper($pdo);
    }

    /**
     * Tear down after class.
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        //self::$pdo = null;
        //self::$permissionMapper = null;
    }

    /**
     * Test new role instance.
     *
     * @return void
     */
    public function testNewRoleInstance(): void
    {
        $this->assertInstanceOf(Permission::class, self::$permissionMapper->create());
    }

    /**
     * Test constructor type casting.
     *
     * @return void
     */
    public function testConstructorTypeCasting(): void
    {
        $permission = self::$permissionMapper->fetchById(1);

        $this->assertIsInt($permission->getId());
        $this->assertIsInt($permission->id);
        $this->assertIsInt($permission->inherited);

        $this->assertGreaterThan(0, $permission->getId());
        $this->assertGreaterThan(0, $permission->id);
    }
}
