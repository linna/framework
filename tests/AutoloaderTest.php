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
        $this->assertEquals(true, (new Autoloader())->register());
    }

    /**
     * @depends testRegister
     */
    public function testNamespace()
    {
        $this->assertInstanceOf(FooClassH::class, new \Linna\Foo\DI\FooClassH());
    }

    public function testClassExist()
    {
        $this->assertEquals(true, class_exists('\Linna\Foo\DI\FooClassH', true));
    }

    public function testBadNamespace()
    {
        $this->assertEquals(false, class_exists('\Linna\Baz\FooClassH', true));
    }

    public function testBadClass()
    {
        $this->assertEquals(false, class_exists('\FooClassNULL', true));
    }

    public function testBadPrefix()
    {
        $this->assertEquals(false, class_exists('Baz\Foo\FooClassNULL', true));
    }
}
