<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Autoloader;
use Linna\Foo\DI\FooClassH;
use PHPUnit\Framework\TestCase;

class AutoloaderTest extends TestCase
{
    protected $autoloader;

    public function setUp()
    {
        $this->autoloader = new Autoloader();
        $this->autoloader->register();
        $this->autoloader->addNamespaces([['Linna\Foo', __DIR__.'/Foo'], ['Linna\Foo_', __DIR__.'/Foo_']]);
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
        $foo = new \Linna\Foo\DI\FooClassH();

        $this->assertInstanceOf(FooClassH::class, $foo);
    }

    public function testClassExist()
    {
        $foo = class_exists('\Linna\Foo\DI\FooClassH', true);

        $this->assertEquals(true, $foo);
    }

    public function testBadNamespace()
    {
        $foo = class_exists('\Linna\Baz\FooClassH', true);

        $this->assertEquals(false, $foo);
    }

    public function testBadClass()
    {
        $foo = class_exists('\FooClassNULL', true);

        $this->assertEquals(false, $foo);
    }

    public function testBadPrefix()
    {
        $foo = class_exists('Baz\Foo\FooClassNULL', true);

        $this->assertEquals(false, $foo);
    }
}
