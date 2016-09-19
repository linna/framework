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
use PHPUnit\Framework\TestCase;

class DomainObjectTest extends TestCase
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
    
    public function testUser()
    {
        $password = new Password();
        $user = new FOOUser($password);
        
        $this->assertInstanceOf(FOOUser::class, $user);
    }
    
    public function testUserSetId()
    {
        $password = new Password();
        $user = new FOOUser($password);
        
        $user->setId(1);
        
        $this->assertEquals(1, $user->getId());
    }
    
    /**
     * @outputBuffering disabled
     * @expectedExceptionMessage objectId is immutable
     */
    public function testUserOverrideId()
    {
        $password = new Password();
        $user = new FOOUser($password);
        
        $user->setId(1);
        $userId = $user->getId();
        
        ob_start();
        
        $user->setId(2);
        
        $message = ob_get_clean();
        
        $this->assertEquals(1, $userId);
        $this->assertEquals('UnexpectedValueException: objectId is immutable', $message);
    }
}
