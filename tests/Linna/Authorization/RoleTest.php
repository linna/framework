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
    public function testConstructorTypeCasting(): void
    {
        $role = self::$role;
        $role->setId(1);

        $this->assertIsInt($role->getId());
        $this->assertIsInt($role->id);
        $this->assertIsInt($role->active);

        $this->assertGreaterThan(0, $role->getId());
        $this->assertGreaterThan(0, $role->id);
    }
}
