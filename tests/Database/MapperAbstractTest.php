<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

use Linna\Autoloader;
use Linna\Auth\Password;

use Linna\FOO\FOOUser;
use Linna\FOO\FOOUserMapper;

class MapperAbstractTest extends PHPUnit_Framework_TestCase
{
    protected $autoloader;
    
    public function __construct()
    {
        $this->autoloader = new Autoloader();
        $this->autoloader->register();
        $this->autoloader->addNamespaces([
           ['Linna\FOO', dirname(__DIR__).'/FOO']
        ]);
    }
    
    public function testUserMapper()
    {
        $password = new Password();
        $mapper = new FOOUserMapper($password);
        
        $this->assertInstanceOf(FOOUserMapper::class, $mapper);
    }
    
    public function testNewUserFromMapper()
    {
        $password = new Password();
        $mapper = new FOOUserMapper($password);
        
        $user = $mapper->create();
        
        $this->assertInstanceOf(FOOUser::class, $user);
    }
    
    public function testSaveNewUserWithMapper()
    {
        $password = new Password();
        $mapper = new FOOUserMapper($password);

        $user = $mapper->create();
        $user->name = 'test';

        $result = $mapper->save($user);

        $this->assertEquals('insert', $result);
    }
    
    public function testSaveExistUserWithMapper()
    {
        $password = new Password();
        $mapper = new FOOUserMapper($password);

        $user = $mapper->findById(5);
        
        $this->assertEquals(5, $user->getId());
        $this->assertEquals('user_5', $user->name);

        $result = $mapper->save($user);

        $this->assertEquals('update', $result);
    }
    
    public function testDeleteUserWithMapper()
    {
        $password = new Password();
        $mapper = new FOOUserMapper($password);

        $user = $mapper->findById(5);
        
        $this->assertEquals(5, $user->getId());
        $this->assertEquals('user_5', $user->name);

        $result = $mapper->delete($user);

        $this->assertEquals('delete', $result);
    }
}