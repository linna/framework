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

/**
 * Domain Object Test
 */
class DomainObjectTest extends TestCase
{
    /**
     * @var User The user class.
     */
    protected $user;

    /**
     * Setup.
     */
    public function setUp(): void
    {
        $this->user = new User(new Password());
    }

    /**
     * Test create new object instance.
     */
    public function testNewObjectInstance(): void
    {
        $this->assertInstanceOf(User::class, $this->user);
    }

    /**
     * Test set object id.
     */
    public function testSetObjectId(): void
    {
        $this->user->setId(1);

        $this->assertEquals(1, $this->user->getId());
    }

    /**
     * Test override object id.
     *
     * @expectedException UnexpectedValueException
     * @expectedExceptionMessage ObjectId property is immutable.
     */
    public function testOverrideObjectId(): void
    {
        $this->user->setId(1);
        $this->user->setId(2);
    }
}
