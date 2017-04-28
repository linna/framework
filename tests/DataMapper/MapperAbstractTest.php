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

class MapperAbstractTest extends TestCase
{
    protected $mapper;

    public function setUp()
    {
        $options = [
            'dsn'      => $GLOBALS['pdo_mysql_dsn'],
            'user'     => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'options'  => [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING],
        ];

        $driver = (new StorageFactory('mysqlpdo', $options))->getConnection();

        $password = new Password();
        $this->mapper = new UserMapper($driver, $password);
    }

    public function testNewMapper()
    {
        $this->assertInstanceOf(UserMapper::class, $this->mapper);
    }

    public function testNewObjectFromMapper()
    {
        $user = $this->mapper->create();

        $this->assertInstanceOf(User::class, $user);
    }

    public function testSaveWithMapper()
    {
        $user = $this->mapper->create();
        $user->name = 'test';

        $result = $this->mapper->save($user);

        $this->assertEquals('insert', $result);
    }

    public function testSaveExistWithMapper()
    {
        $user = $this->mapper->fetchById(1);

        $this->assertEquals(1, $user->getId());
        $this->assertEquals('root', $user->name);

        $result = $this->mapper->save($user);

        $this->assertEquals('update', $result);
    }

    public function testDeleteWithMapper()
    {
        $user = $this->mapper->fetchById(1);

        $this->assertEquals(1, $user->getId());
        $this->assertEquals('root', $user->name);

        $result = $this->mapper->delete($user);

        $this->assertEquals('delete', $result);
    }
}
