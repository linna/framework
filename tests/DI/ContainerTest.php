<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\DI\Container;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = new Container();

        $this->container->set('FooClass', function () {
            return new \stdClass();
        });

        $this->container['FooClass'] = function () {
            return new \stdClass();
        };

        $this->container->FooClass = function () {
            return new \stdClass();
        };
    }

    public function testContainerGet()
    {
        $FooClass = $this->container->get('FooClass');

        $this->assertInstanceOf(stdClass::class, $FooClass);
    }

    public function testContainerNotFoundGet()
    {
        $this->expectException(\Exception::class);
        $BarClass = $this->container->get('BarClass');
    }

    public function testContainerHas()
    {
        $this->assertEquals(true, $this->container->has('FooClass'));
        $this->assertEquals(false, $this->container->has('BarClass'));
    }

    public function testContainerDelete()
    {
        $this->assertEquals(true, $this->container->has('FooClass'));

        $this->container->delete('FooClass');

        $this->assertEquals(false, $this->container->has('FooClass'));
        $this->assertEquals(false, $this->container->delete('FooClass'));
    }

    public function testArrayContainerGet()
    {
        $FooClass = $this->container['FooClass'];

        $this->assertInstanceOf(stdClass::class, $FooClass);
    }

    public function testArrayContainerNotFoundGet()
    {
        $this->expectException(\Exception::class);
        $BarClass = $this->container['BarClass'];
    }

    public function testArrayContainerHas()
    {
        $this->assertEquals(true, isset($this->container['FooClass']));
        $this->assertEquals(false, isset($this->container['BarClass']));
    }

    public function testArrayContainerDelete()
    {
        $this->assertEquals(true, isset($this->container['FooClass']));

        unset($this->container['FooClass']);

        $this->assertEquals(false, isset($this->container['FooClass']));
    }

    public function testPropertyContainerGet()
    {
        $FooClass = $this->container->FooClass;

        $this->assertInstanceOf(stdClass::class, $FooClass);
    }

    public function testPropertyContainerNotFoundGet()
    {
        $this->expectException(\Exception::class);
        $BarClass = $this->container->BarClass;
    }

    public function testPropertyContainerHas()
    {
        $this->assertEquals(true, isset($this->container->FooClass));
        $this->assertEquals(false, isset($this->container->BarClass));
    }

    public function testPropertyContainerDelete()
    {
        $this->assertEquals(true, isset($this->container->FooClass));

        unset($this->container->FooClass);

        $this->assertEquals(false, isset($this->container->FooClass));
    }
}
