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
    protected $mapper;

    /**
     * Setup.
     */
    public function setUp(): void
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

        $this->mapper = new UserMapper(
            (new StorageFactory('pdo', $options))->get(),
            new Password()
        );
    }

    /**
     * Test create new object instance.
     */
    public function testNewObjectInstance(): void
    {
        $this->assertInstanceOf(UserMapper::class, $this->mapper);
    }

    /**
     * Test create new domain object with mapper.
     */
    public function testCreateDomainObjectWithMapper(): void
    {
        $this->assertInstanceOf(User::class, $this->mapper->create());
    }

    /**
     * Test save domain object with mapper.
     */
    public function testSaveDomainObjectWithMapper(): void
    {
        /** @var User User Class. */
        $user = $this->mapper->create();
        $user->name = 'test_user_create';
        $user->setPassword('password');

        $this->mapper->save($user);

        /** @var User User Class. */
        $newUser = $this->mapper->fetchByName('test_user_create');

        $this->assertEquals('test_user_create', $newUser->name);

        $this->mapper->delete($newUser);
    }

    /**
     * Test update domain object with mapper.
     */
    public function testUpdateDomainObjectWithMapper(): void
    {
        /** @var User User Class. */
        $user = $this->mapper->create();
        $user->name = 'test_user_update';
        $user->setPassword('password');

        $this->mapper->save($user);

        /** @var User User Class. */
        $newUser = $this->mapper->fetchByName('test_user_update');
        $newUserId = $newUser->getId();

        $this->assertEquals('test_user_update', $newUser->name);

        $newUser->name = 'test_user_updated';
        $this->mapper->save($newUser);

        /** @var User User Class. */
        $newUserUpdated = $this->mapper->fetchById($newUserId);

        $this->assertEquals('test_user_updated', $newUserUpdated->name);

        $this->mapper->delete($newUser);
    }

    /**
     * Test delete domain object with mapper.
     */
    public function testDeleteDomainObjectWithMapper(): void
    {
        /** @var User User Class. */
        $user = $this->mapper->create();
        $user->name = 'test_user_delete';
        $user->setPassword('password');

        $this->mapper->save($user);

        /** @var User User Class. */
        $newUser = $this->mapper->fetchByName('test_user_delete');

        $this->assertEquals('test_user_delete', $newUser->name);

        $this->mapper->delete($newUser);

        /** @var User User Class. */
        $nullUser = $this->mapper->fetchByName('test_user_delete');

        $this->assertInstanceOf(NullDomainObject::class, $nullUser);
    }
}
