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

class PermissionTest extends TestCase
{
    /** @var Permission The permission instance */
    protected static Permission $permission;

    /**
     * Set up before class.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$permission = new Permission(
            name:            'test_permission',
            description:     'test_permission_description',
            inherited:       0,
            created:         new DateTimeImmutable(),
            lastUpdate:      new DateTimeImmutable()
        );
    }

    /**
    * Test new permission instance.
    *
     * @return void
    */
    public function testNewRoleInstance(): void
    {
        $permission = self::$permission;

        $this->assertInstanceOf(Permission::class, $permission);
        $this->assertInstanceOf(DateTimeImmutable::class, $permission->created);
        $this->assertInstanceOf(DateTimeImmutable::class, $permission->lastUpdate);

        //id null because not saved into persistent storage
        $this->assertSame(null, $permission->id);
        $this->assertSame('test_permission', $permission->name);
        $this->assertSame('test_permission_description', $permission->description);
        $this->assertSame(0, $permission->inherited);
    }

    /**
     * Test constructor type casting.
     *
     * @return void
     */
    public function testConstructorTypeCasting(): void
    {
        $permission = self::$permission;
        $permission->setId(1);

        $this->assertIsInt($permission->getId());
        $this->assertIsInt($permission->id);
        $this->assertIsInt($permission->inherited);

        $this->assertGreaterThan(0, $permission->getId());
        $this->assertGreaterThan(0, $permission->id);
    }
}
