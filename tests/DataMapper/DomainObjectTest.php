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

class DomainObjectTest extends TestCase
{
    protected $user;

    public function setUp()
    {
        $password = new Password();
        $this->user = new User($password);
    }

    public function testNewUser()
    {
        $this->assertInstanceOf(User::class, $this->user);
    }

    public function testUserSetId()
    {
        $this->user->setId(1);

        $this->assertEquals(1, $this->user->getId());
    }

    /**
     * @outputBuffering disabled
     * @expectedExceptionMessage objectId is immutable
     */
    public function testUserOverrideId()
    {
        $this->user->setId(1);
        $userId = $this->user->getId();

        ob_start();

        $this->user->setId(2);

        $message = ob_get_clean();

        $this->assertEquals(1, $userId);
        $this->assertEquals('UnexpectedValueException: objectId is immutable', $message);
    }
}
