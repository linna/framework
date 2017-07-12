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
use PHPUnit\Framework\TestCase;

/**
 * Autoloader Test
 */
class AutoloaderTest extends TestCase
{
    /**
     * Setup.
     */
    public function setUp()
    {
        $autoloader = new Autoloader();
        $autoloader->register();
        $autoloader->addNamespaces([
            ['Linna\FooAuto', __DIR__.'/FooClass'],
            ['Linna\Foo_', __DIR__.'/FooClass'],
            ['Baz\Foo', __DIR__.'/FooClass']
        ]);
    }

    /**
     * Test class exist.
     */
    public function testAutoloadCorrectClassWithCorrectNamespace()
    {
        $this->assertTrue(class_exists('Linna\FooAuto\Autoload\FooClassAuto', true));
    }

    /**
     * Test class exist.
     */
    public function testAutoloadWrongClassWithCorrectNamespace()
    {
        $this->assertNotTrue(class_exists('Linna\FooAuto\Autoload\FooClassAuto2', true));
    }

    /**
     * Test bad namespace.
     */
    public function testAutoloadCorrectClassWithWrongNamespace()
    {
        $this->assertNotTrue(class_exists('Linna\Foo\Baz\FooClassAuto', true));
    }

    /**
     * Test bad class.
     */
    public function testAutoloadWrongClassWithWrongNamespace()
    {
        $this->assertNotTrue(class_exists('Linna\Foo_\Autoload\FooClassAuto2', true));
    }
}
