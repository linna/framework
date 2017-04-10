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
use Linna\Foo\Mappers\UserMapper;
use Linna\Storage\StorageFactory;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected $userMapper;

    public function setUp()
    {
        $options = [
            'dsn'      => $GLOBALS['pdo_mysql_dsn'],
            'user'     => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'options'  => [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING],
        ];

        $pdo = (new StorageFactory())->createConnection('mysqlpdo', $options);

        $password = new Password();

        $this->userMapper = new UserMapper($pdo, $password);
    }

    public function testCreateUser()
    {
        $user = $this->userMapper->create();

        $this->assertInstanceOf(User::class, $user);
    }

    public function testSetPassword()
    {
        $user = $this->userMapper->create();

        $user->setPassword('password');

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(true, password_verify('password', $user->password));
    }

    public function testChangePassword()
    {
        $user = $this->userMapper->create();

        $user->setPassword('password');

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(true, $user->chagePassword('new_password', 'password'));
        $this->assertEquals(true, $user->chagePassword('other_new_password', 'new_password'));
        $this->assertEquals(false, $user->chagePassword('password', 'badpassword'));
    }
}
