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
use Linna\FOO\FOOClassA;
use PHPUnit\Framework\TestCase;

class AutoloaderTest extends TestCase
{
    protected $autoloader;
    
    public function __construct()
    {
        $this->autoloader = new Autoloader();
        $this->autoloader->register();
    }
    
    public function testRegister()
    {
        $this->autoloader = new Autoloader();
        $result = $this->autoloader->register();
        
        $this->assertEquals(true, $result);
    }
    
    /**
     * @depends testRegister
     */
    public function testNamespaces()
    {
        $this->autoloader->addNamespaces([
            ['Linna\FOOBar', __DIR__.'/FOOBar'],
            ['Linna\FOO', __DIR__.'/FOO'],
            ['Linna', dirname(__DIR__) . '/src/Linna'],
            ['Linna', dirname(__DIR__) . '/src/Linna/Shared'],
            ['Linna\Auth', dirname(__DIR__) . '/src/Linna/Auth'],
            ['Linna\DI', dirname(__DIR__) . '/src/Linna/DI'],
            ['Linna\Database', dirname(__DIR__) . '/src/Linna/Database'],
            ['Linna\DataMapper', dirname(__DIR__) . '/src/Linna/DataMapper'],
            ['Linna\Http', dirname(__DIR__) . '/src/Linna/Http'],
            ['Linna\Mvc', dirname(__DIR__) . '/src/Linna/Mvc'],
            ['Linna\Session', dirname(__DIR__) . '/src/Linna/Session']
        ]);
        
        $foo = new FOOClassA();
        
        $this->assertInstanceOf(FOOClassA::class, $foo);
    }
}
