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
use Linna\Authentication\User;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

/**
 * Domain Object Test
 */
class DomainObjectTest extends TestCase
{
    /**
     * @var User The user class.
     */
    protected static $user;

    /**
     * Set up before class.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$user = new User(new Password());
    }

    /**
     * Tear down after class.
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        self::$user = null;
    }

    /**
     * Test create new object instance.
     *
     * @return void
     */
    public function testNewObjectInstance(): void
    {
        $this->assertInstanceOf(User::class, self::$user);
    }

    /**
     * Test set object id.
     *
     * @return void
     */
    public function testSetObjectId(): void
    {
        self::$user->setId(1);

        $this->assertEquals(1, self::$user->getId());
    }

    /**
     * Test override object id.
     *
     * @return void
     */
    public function testOverrideObjectId(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage("ObjectId property is immutable.");

        self::$user->setId(1);
        self::$user->setId(2);
    }
}
