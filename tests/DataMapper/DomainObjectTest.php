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
use Linna\Auth\User;
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
    public function setUp()
    {
        $this->user = new User(new Password());
    }

    /**
     * Test create new object instance.
     */
    public function testNewObjectInstance()
    {
        $this->assertInstanceOf(User::class, $this->user);
    }

    /**
     * Test set object id.
     */
    public function testSetObjectId()
    {
        $this->user->setId(1);

        $this->assertEquals(1, $this->user->getId());
    }

    /**
     * Test override object id.
     *
     * @expectedException UnexpectedValueException
     */
    public function testOverrideObjectId()
    {
        $this->user->setId(1);
        $this->user->setId(2);
    }
}
