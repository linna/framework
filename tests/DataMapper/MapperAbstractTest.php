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
use Linna\Authentication\UserMapper;
use Linna\DataMapper\NullDomainObject;
use Linna\Storage\StorageFactory;
use PDO;
use PHPUnit\Framework\TestCase;

/**
 * Mapper Abstract Test
 *
 */
class MapperAbstractTest extends TestCase
{
    /**
     * @var UserMapper The user mapper
     */
    protected static $userMapper;

    /**
     * Set up before class.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        $options = [
            'dsn'      => $GLOBALS['pdo_mysql_dsn'],
            'user'     => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'options'  => [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT         => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
            ],
        ];

        self::$userMapper = new UserMapper(
            (new StorageFactory('pdo', $options))->get(),
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
        self::$userMapper = null;
    }

    /**
     * Test create new object instance.
     *
     * @return void
     */
    public function testNewObjectInstance(): void
    {
        $this->assertInstanceOf(UserMapper::class, self::$userMapper);
    }

    /**
     * Test create new domain object with mapper.
     *
     * @return void
     */
    public function testCreateDomainObjectWithMapper(): void
    {
        $this->assertInstanceOf(User::class, self::$userMapper->create());
    }

    /**
     * Test save domain object with mapper.
     *
     * @return void
     */
    public function testSaveDomainObjectWithMapper(): void
    {
        /** @var User User Class. */
        $user = self::$userMapper->create();
        $user->name = 'test_user_create';
        $user->uuid = $this->v4();
        $user->setPassword('password');

        //var_dump($user);

        self::$userMapper->save($user);

        /** @var User User Class. */
        $newUser = self::$userMapper->fetchByName('test_user_create');

        $this->assertEquals('test_user_create', $newUser->name);

        self::$userMapper->delete($newUser);
    }

    /**
     * Test update domain object with mapper.
     *
     * @return void
     */
    public function testUpdateDomainObjectWithMapper(): void
    {
        /** @var User User Class. */
        $user = self::$userMapper->create();
        $user->name = 'test_user_update';
        $user->uuid = $this->v4();
        $user->setPassword('password');

        self::$userMapper->save($user);

        /** @var User User Class. */
        $newUser = self::$userMapper->fetchByName('test_user_update');
        $newUserId = $newUser->getId();

        $this->assertEquals('test_user_update', $newUser->name);

        $newUser->name = 'test_user_updated';
        self::$userMapper->save($newUser);

        /** @var User User Class. */
        $newUserUpdated = self::$userMapper->fetchById($newUserId);

        $this->assertEquals('test_user_updated', $newUserUpdated->name);

        self::$userMapper->delete($newUser);
    }

    /**
     * Test delete domain object with mapper.
     *
     * @return void
     */
    public function testDeleteDomainObjectWithMapper(): void
    {
        /** @var User User Class. */
        $user = self::$userMapper->create();
        $user->name = 'test_user_delete';
        $user->uuid = $this->v4();
        $user->setPassword('password');

        self::$userMapper->save($user);

        /** @var User User Class. */
        $newUser = self::$userMapper->fetchByName('test_user_delete');

        $this->assertEquals('test_user_delete', $newUser->name);

        self::$userMapper->delete($newUser);

        /** @var User User Class. */
        $nullUser = self::$userMapper->fetchByName('test_user_delete');

        $this->assertInstanceOf(NullDomainObject::class, $nullUser);
    }
    
    /**
     * Generate a UUID v4.
     * https://en.wikipedia.org/wiki/Universally_unique_identifier
     *
     * @return string
     */
    public function v4(): string
    {
        return sprintf(
            '%s-%s-%04x-%04x-%s',
            // 8 hex characters
            bin2hex(random_bytes(4)),
            // 4 hex characters
            bin2hex(random_bytes(2)),
            // "4" for the UUID version + 3 hex characters
            mt_rand(0, 0x0fff) | 0x4000,
            // (8, 9, a, or b) for the UUID variant + 3 hex characters
            mt_rand(0, 0x3fff) | 0x8000,
            // 12 hex characters
            bin2hex(random_bytes(6))
        );
    }
}
