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
           ['Linna\FOO', __DIR__.'/FOO']
        ]);
        
        $foo = new FOOClassA();
        
        $this->assertInstanceOf(FOOClassA::class, $foo);
    }
}
