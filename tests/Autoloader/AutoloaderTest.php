<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Autoloader;
use PHPUnit\Framework\TestCase;

/**
 * Autoloader Test
 */
class AutoloaderTest extends TestCase
{
    /**
     * Test load mapped file.
     */
    public function testLoadMappedFileTrue()
    {
        $autoloader = new Autoloader();
        
        $this->assertTrue($autoloader->register());
        
        $autoloader->addNamespaces([
            ['Linna\TestHelper', dirname(__DIR__).'/TestHelper'],
            ['Linna\Foo_', dirname(__DIR__).'/FooClass'],
            ['Baz\Foo', dirname(__DIR__).'/FooClass']
        ]);
        
        $this->assertTrue($autoloader->loadClass(Linna\TestHelper\DI\ClassI::class));
        
        $this->assertTrue($autoloader->unregister());
    }
    
    /**
    * Test load mapped file fail.
    */
    public function testLoadMappedFileFalse()
    {
        $autoloader = new Autoloader();
        
        $this->assertTrue($autoloader->register());
        
        $autoloader->addNamespaces([
            ['Linna\TestHelper', dirname(__DIR__).'/TestHelper'],
            ['Linna\Foo_', dirname(__DIR__).'/FooClass'],
            ['Baz\Foo', dirname(__DIR__).'/FooClass']
        ]);
        
        $this->assertFalse($autoloader->loadClass('Linna\TestHelper\DI\NotExistClass'));
        
        $this->assertTrue($autoloader->unregister());
    }
    
    /**
     * Test load mapped file no prefix.
     */
    public function testLoadMappedFileNoPrefix()
    {
        $autoloader = new Autoloader();
        
        $this->assertTrue($autoloader->register());
        
        $autoloader->addNamespaces([
            ['Linna\TestHelper', dirname(__DIR__).'/TestHelper'],
            ['Linna\Foo_', dirname(__DIR__).'/FooClass'],
            ['Baz\Foo', dirname(__DIR__).'/FooClass']
        ]);
        
        $this->assertFalse($autoloader->loadClass('Linna\NoPrefix\ClassI'));
        
        $this->assertTrue($autoloader->unregister());
    }
    
    /**
     * Test load mapped file with one namespace.
     */
    public function testLoadMappedFileTrueWithOneNamespace()
    {
        $autoloader = new Autoloader();
        
        $this->assertTrue($autoloader->register());
        
        $autoloader->addNamespaces([
            ['Linna\TestHelper', dirname(__DIR__).'/TestHelper'],
        ]);
        
        $this->assertTrue($autoloader->loadClass(Linna\TestHelper\DI\ClassH::class));
        
        $this->assertTrue($autoloader->unregister());
    }
    
    /**
     * Test load mapped file fail with one namespace.
     */
    public function testLoadMappedFileFalseWithOneNamespace()
    {
        $autoloader = new Autoloader();
        
        $this->assertTrue($autoloader->register());
        
        $autoloader->addNamespaces([
            ['Linna\TestHelper', dirname(__DIR__).'/TestHelper'],
        ]);
        
        $this->assertFalse($autoloader->loadClass('Linna\NoPrefix\ClassH'));
        
        $this->assertTrue($autoloader->unregister());
    }
    
    /**
     * Test load mapped file no prefix with one namespace.
     */
    public function testLoadMappedFileNoPrefixWithOneNamespace()
    {
        $autoloader = new Autoloader();
        
        $this->assertTrue($autoloader->register());
        
        $autoloader->addNamespaces([
            ['Linna\TestHelper', dirname(__DIR__).'/TestHelper'],
        ]);
        
        $this->assertFalse($autoloader->loadClass('Linna\NoPrefix\NotExistClass'));
        
        $this->assertTrue($autoloader->unregister());
    }
}
