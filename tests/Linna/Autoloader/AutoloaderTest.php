<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Autoloader;

use Linna\Autoloader;
use PHPUnit\Framework\TestCase;

/**
 * Autoloader Test
 */
class AutoloaderTest extends TestCase
{
    /**
     * Test load mapped file.
     *
     * @return void
     */
    public function testLoadMappedFileTrue(): void
    {
        $autoloader = new Autoloader();

        $this->assertTrue($autoloader->register());

        $autoloader->addNamespaces([
            ['Linna\TestHelper', \dirname(__DIR__).'/TestHelper'],
            ['Linna\Foo_', \dirname(__DIR__).'/FooClass'],
            ['Baz\Foo', \dirname(__DIR__).'/FooClass']
        ]);

        $this->assertTrue($autoloader->loadClass(\Linna\TestHelper\Container\ClassI::class));

        $this->assertTrue($autoloader->unregister());
    }

    /**
    * Test load mapped file fail.
     *
     * @return void
    */
    public function testLoadMappedFileFalse(): void
    {
        $autoloader = new Autoloader();

        $this->assertTrue($autoloader->register());

        $autoloader->addNamespaces([
            ['Linna\TestHelper', \dirname(__DIR__).'/TestHelper'],
            ['Linna\Foo_', \dirname(__DIR__).'/FooClass'],
            ['Baz\Foo', \dirname(__DIR__).'/FooClass']
        ]);

        $this->assertFalse($autoloader->loadClass('Linna\TestHelper\Container\NotExistClass'));

        $this->assertTrue($autoloader->unregister());
    }

    /**
     * Test load mapped file no prefix.
     *
     * @return void
     */
    public function testLoadMappedFileNoPrefix(): void
    {
        $autoloader = new Autoloader();

        $this->assertTrue($autoloader->register());

        $autoloader->addNamespaces([
            ['Linna\TestHelper', \dirname(__DIR__).'/TestHelper'],
            ['Linna\Foo_', \dirname(__DIR__).'/FooClass'],
            ['Baz\Foo', \dirname(__DIR__).'/FooClass']
        ]);

        $this->assertFalse($autoloader->loadClass('Linna\NoPrefix\ClassI'));

        $this->assertTrue($autoloader->unregister());
    }

    /**
     * Test load mapped file with one namespace.
     *
     * @return void
     */
    public function testLoadMappedFileTrueWithOneNamespace(): void
    {
        $autoloader = new Autoloader();

        $this->assertTrue($autoloader->register());

        $autoloader->addNamespaces([
            ['Linna\TestHelper', \dirname(__DIR__).'/TestHelper'],
        ]);

        $this->assertTrue($autoloader->loadClass(\Linna\TestHelper\Container\ClassH::class));

        $this->assertTrue($autoloader->unregister());
    }

    /**
     * Test load mapped file fail with one namespace.
     *
     * @return void
     */
    public function testLoadMappedFileFalseWithOneNamespace(): void
    {
        $autoloader = new Autoloader();

        $this->assertTrue($autoloader->register());

        $autoloader->addNamespaces([
            ['Linna\TestHelper', \dirname(__DIR__).'/TestHelper'],
        ]);

        $this->assertFalse($autoloader->loadClass('Linna\NoPrefix\ClassH'));

        $this->assertTrue($autoloader->unregister());
    }

    /**
     * Test load mapped file no prefix with one namespace.
     *
     * @return void
     */
    public function testLoadMappedFileNoPrefixWithOneNamespace(): void
    {
        $autoloader = new Autoloader();

        $this->assertTrue($autoloader->register());

        $autoloader->addNamespaces([
            ['Linna\TestHelper', \dirname(__DIR__).'/TestHelper'],
        ]);

        $this->assertFalse($autoloader->loadClass('Linna\NoPrefix\NotExistClass'));

        $this->assertTrue($autoloader->unregister());
    }
}
