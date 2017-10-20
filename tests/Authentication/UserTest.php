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
use Linna\Authentication\User;
use Linna\Foo\Mappers\UserMapper;
use Linna\Storage\StorageFactory;
use PHPUnit\Framework\TestCase;

/**
 * User Test.
 */
class UserTest extends TestCase
{
    /**
     * @var UserMapper The user mapper
     */
    protected $userMapper;

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

        $this->userMapper = new UserMapper(
            (new StorageFactory('pdo', $options))->get(),
            new Password()
        );
    }

    /**
     * Test new user instance.
     */
    public function testNewUserInstance()
    {
        $this->assertInstanceOf(User::class, $this->userMapper->create());
    }

    /**
     * Test set user password.
     */
    public function testSetUserPassword()
    {
        $user = $this->userMapper->create();

        $user->setPassword('password');

        $this->assertInstanceOf(User::class, $user);
        
        $this->assertTrue(password_verify('password', $user->password));
    }

    /**
     * Test change user password.
     */
    public function testChangeUserPassword()
    {
        $user = $this->userMapper->create();

        $user->setPassword('old_password');

        $this->assertInstanceOf(User::class, $user);
        
        $this->assertTrue($user->chagePassword('new_password', 'old_password'));
        $this->assertTrue($user->chagePassword('other_new_password', 'new_password'));
        $this->assertFalse($user->chagePassword('password', 'wrong_password'));
    }
}
