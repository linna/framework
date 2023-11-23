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
use Linna\Authentication\Password;
use PHPUnit\Framework\TestCase;

/**
 * User Test.
 */
class UserTest extends TestCase
{
    /** @var User The user instance */
    protected static User $user;

    /**
     * Set up before class.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$user = new User(
            passwordUtility: new Password(),
            name:            'test_user',
            description:     'test_user_description',
            email:           'test_user@email.com',
            active:          1,
            created:         new DateTimeImmutable(),
            lastUpdate:      new DateTimeImmutable()
        );
    }

    /**
     * Test new user instance.
     *
     * @return void
     */
    public function testNewUserInstance(): void
    {
        $user = self::$user;

        $this->assertInstanceOf(User::class, $user);
        $this->assertInstanceOf(DateTimeImmutable::class, $user->created);
        $this->assertInstanceOf(DateTimeImmutable::class, $user->lastUpdate);

        //id null because not saved into persistent storage
        $this->assertSame(null, $user->id);
        $this->assertSame('test_user', $user->name);
        $this->assertSame('test_user_description', $user->description);
        $this->assertSame('test_user@email.com', $user->email);
        $this->assertSame(1, $user->active);
    }

    /**
     * Test set user password.
     *
     * @return void
     */
    public function testSetUserPassword(): void
    {
        $user = self::$user;

        $user->setPassword('password');

        $this->assertTrue(\password_verify('password', $user->password));
    }

    /**
     * Test change user password.
     *
     * @return void
     */
    public function testChangeUserPassword(): void
    {
        $user = self::$user;
        ;

        $user->setPassword('old_password');

        $this->assertTrue($user->changePassword('new_password', 'old_password'));
        $this->assertTrue($user->changePassword('other_new_password', 'new_password'));
        // other_new_password was the current old password
        $this->assertFalse($user->changePassword('password', 'wrong_password'));
    }
}
