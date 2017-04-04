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
use Linna\Foo\FooUserMapper;
use PHPUnit\Framework\TestCase;

class MapperAbstractTest extends TestCase
{
    protected $mapper;

    public function setUp()
    {
        $password = new Password();
        $this->mapper = new FooUserMapper($password);
    }

    public function testNewMapper()
    {
        $this->assertInstanceOf(FooUserMapper::class, $this->mapper);
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
        $user = $this->mapper->findById(5);

        $this->assertEquals(5, $user->getId());
        $this->assertEquals('user_5', $user->name);

        $result = $this->mapper->save($user);

        $this->assertEquals('update', $result);
    }

    public function testDeleteWithMapper()
    {
        $user = $this->mapper->findById(5);

        $this->assertEquals(5, $user->getId());
        $this->assertEquals('user_5', $user->name);

        $result = $this->mapper->delete($user);

        $this->assertEquals('delete', $result);
    }
}
