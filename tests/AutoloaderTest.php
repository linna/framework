<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

declare(strict_types=1);

use Linna\Autoloader;
use Linna\FOO\FOOClassH;
use PHPUnit\Framework\TestCase;

class AutoloaderTest extends TestCase
{
    protected $autoloader;
    
    public function setUp()
    {
        $this->autoloader = new Autoloader();
        $this->autoloader->register();
        $this->autoloader->addNamespaces([['Linna\FOO', __DIR__.'/FOO']]);
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
    public function testNamespace()
    {
        $foo = new \Linna\FOO\FOOClassH();
        
        $this->assertInstanceOf(FOOClassH::class, $foo);
    }
    
    public function testClassExist()
    {
        $foo = class_exists('\Linna\FOO\FOOClassH', true);
        
        $this->assertEquals(true, $foo);
    }
    
    public function testBadNamespace()
    {
        $foo = class_exists('\Linna\BAZ\FOOClassH', true);
        
        $this->assertEquals(false, $foo);
    }
    
    public function testBadClass()
    {
        $foo = class_exists('\FOOClassNULL', true);
        
        $this->assertEquals(false, $foo);
    }
    
    public function testBadPrefix()
    {
        $foo = class_exists('BAZ\Foo\FOOClassNULL', true);
        
        $this->assertEquals(false, $foo);
    }
}
