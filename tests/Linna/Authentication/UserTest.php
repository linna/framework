<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Authentication;

//use Linna\Authentication\Password;
//use Linna\Authentication\User;
//use Linna\Authentication\UserMapper;
use Linna\Storage\StorageFactory;
//use PDO;
use PHPUnit\Framework\TestCase;
use Linna\TestHelper\Pdo\PdoOptionsFactory;

/**
 * User Test.
 */
class UserTest extends TestCase
{
    /** @var UserMapper The user mapper */
    protected static UserMapper $userMapper;

    /**
     * Set up before class.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        /*$options = [
            'dsn'      => $GLOBALS['pdo_mysql_dsn'],
            'user'     => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'options'  => [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT         => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
            ],
        ];*/

        self::$userMapper = new UserMapper(
            (new StorageFactory('pdo', PdoOptionsFactory::getOptions()))->get(),
            new Password()
        );
    }

    /**
     * Tear down after class.
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        //self::$userMapper = null;
    }

    /**
     * Test new user instance.
     *
     * @return void
     */
    public function testNewUserInstance(): void
    {
        $this->assertInstanceOf(User::class, self::$userMapper->create());
    }

    /**
     * Test constructor type casting.
     *
     * @return void
     */
    public function testConstructorTypeCasting(): void
    {
        $user = self::$userMapper->fetchByName('root');

        $this->assertIsInt($user->getId());
        $this->assertIsInt($user->active);

        $this->assertGreaterThan(0, $user->getId());
    }

    /**
     * Test set user password.
     *
     * @return void
     */
    public function testSetUserPassword(): void
    {
        /** @var User User Class. */
        $user = self::$userMapper->create();

        $user->setPassword('password');

        $this->assertInstanceOf(User::class, $user);

        $this->assertTrue(\password_verify('password', $user->password));
    }

    /**
     * Test change user password.
     *
     * @return void
     */
    public function testChangeUserPassword(): void
    {
        /** @var User User Class. */
        $user = self::$userMapper->create();

        $user->setPassword('old_password');

        $this->assertInstanceOf(User::class, $user);

        $this->assertTrue($user->changePassword('new_password', 'old_password'));
        $this->assertTrue($user->changePassword('other_new_password', 'new_password'));
        $this->assertFalse($user->changePassword('password', 'wrong_password'));
    }
}
