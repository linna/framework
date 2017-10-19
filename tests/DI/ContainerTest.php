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
use Linna\Foo\DI\FooClassACache;
use PHPUnit\Framework\TestCase;

/**
 * Container test.
 */
class ContainerTest extends TestCase
{
    /**
     * Values Provider.
     *
     * @return array
     */
    public function valuesProvider() : array
    {
        return [
            ['string', 'Hello World'],
            ['int', 1],
            ['float', 1.1],
            ['array', [1, 2, 3, 4]],
            ['closure', function () {
                return 'Hello World';
            }],
            [\Linna\Foo\DI\FooClassACache::class, new FooClassACache('Hello World')],
        ];
    }

    /**
     * Test set and get callig method.
     *
     * @param string $key
     * @param type   $value
     *
     * @dataProvider valuesProvider
     */
    public function testSetAndGetWithMethodCall(string $key, $value)
    {
        $container = new Container();

        $container->set($key, $value);

        $this->assertEquals($value, $container->get($key));
    }

    /**
     * Test set and get utilizing array sntax.
     *
     * @param string $key
     * @param type   $value
     *
     * @dataProvider valuesProvider
     */
    public function testSetAndGetWithArraySyntax(string $key, $value)
    {
        $container = new Container();

        $container[$key] = $value;

        $this->assertEquals($value, $container[$key]);
    }

    /**
     * Test set and get utilizing property sntax.
     *
     * @param string $key
     * @param type   $value
     *
     * @dataProvider valuesProvider
     */
    public function testSetAndGetWithPropertySyntax(string $key, $value)
    {
        $container = new Container();

        $container->$key = $value;

        $this->assertEquals($value, $container->$key);
    }

    /**
     * Test has callig method.
     *
     * @param string $key
     * @param type   $value
     *
     * @dataProvider valuesProvider
     */
    public function testHasWithMethodCall(string $key, $value)
    {
        $container = new Container();

        $container->set($key, $value);

        $this->assertTrue($container->has($key));
    }

    /**
     * Test has utilizing array sntax.
     *
     * @param string $key
     * @param type   $value
     *
     * @dataProvider valuesProvider
     */
    public function testHasWithArraySyntax(string $key, $value)
    {
        $container = new Container();

        $container[$key] = $value;

        $this->assertTrue(isset($container[$key]));
    }

    /**
     * Test has utilizing property sntax.
     *
     * @param string $key
     * @param type   $value
     *
     * @dataProvider valuesProvider
     */
    public function testHasWithWithPropertySyntax(string $key, $value)
    {
        $container = new Container();

        $container->$key = $value;

        $this->assertTrue(isset($container->$key));
    }

    /**
     * Test delete callig method.
     *
     * @param string $key
     * @param type   $value
     *
     * @dataProvider valuesProvider
     */
    public function testDeleteUnexisting(string $key, $value)
    {
        $this->assertFalse((new Container())->delete($key));
    }

    /**
     * Test delete callig method.
     *
     * @param string $key
     * @param type   $value
     *
     * @dataProvider valuesProvider
     */
    public function testDeleteWithMethodCall(string $key, $value)
    {
        $container = new Container();

        $container->set($key, $value);

        $this->assertTrue($container->has($key));

        $this->assertTrue($container->delete($key));

        $this->assertFalse($container->has($key));
    }

    /**
     * Test delete utilizing array sntax.
     *
     * @param string $key
     * @param type   $value
     *
     * @dataProvider valuesProvider
     */
    public function testDeleteWithArraySyntax(string $key, $value)
    {
        $container = new Container();

        $container[$key] = $value;

        $this->assertTrue(isset($container[$key]));

        unset($container[$key]);

        $this->assertFalse(isset($container[$key]));
    }

    /**
     * Test delete utilizing property sntax.
     *
     * @param string $key
     * @param type   $value
     *
     * @dataProvider valuesProvider
     */
    public function testDeleteWithPropertySyntax(string $key, $value)
    {
        $container = new Container();

        $container->$key = $value;

        $this->assertTrue(isset($container->$key));

        unset($container->$key);

        $this->assertFalse(isset($container->$key));
    }

    /**
     * Test get unexisting callig method.
     *
     * @param string $key
     * @param type   $value
     *
     * @dataProvider valuesProvider
     * @expectedException Linna\DI\Exception\NotFoundException
     */
    public function testGetUnexistingWithMethodCall(string $key, $value)
    {
        $container = new Container();
        $container->get($key);
    }

    /**
     * Test get unexisting utilizing array sntax.
     *
     * @param string $key
     * @param type   $value
     *
     * @dataProvider valuesProvider
     * @expectedException Linna\DI\Exception\NotFoundException
     */
    public function testGetUnexistingWithArraySyntax(string $key, $value)
    {
        $container = new Container();
        $foo = $container[$key];
    }

    /**
     * Test get unexisting utilizing property sntax.
     *
     * @param string $key
     * @param type   $value
     *
     * @dataProvider valuesProvider
     * @expectedException Linna\DI\Exception\NotFoundException
     */
    public function testGetUnexistingWithPropertySyntax(string $key, $value)
    {
        $container = new Container();
        $foo = $container->$key;
    }

    /**
     * Class Provider.
     *
     * @return array
     */
    public function classProvider() : array
    {
        return [
            [\Linna\Foo\DI\FooClassResObject::class],
            [\Linna\Foo\DI\FooClassB::class],
            [\Linna\Foo\DI\FooClassC::class],
            [\Linna\Foo\DI\FooClassD::class],
            [\Linna\Foo\DI\FooClassE::class],
            [\Linna\Foo\DI\FooClassF::class],
            [\Linna\Foo\DI\FooClassG::class],
            [\Linna\Foo\DI\FooClassH::class],
            [\Linna\Foo\DI\FooClassI::class],
        ];
    }

    /**
     * Test class resolving.
     *
     * @dataProvider classProvider
     *
     * @param string $class
     */
    public function testResolve($class)
    {
        $this->assertInstanceOf($class, (new Container())->resolve($class));
    }

    /**
     * Test resolving class with pre cached dependencies.
     */
    public function testResolveWithCache()
    {
        $container = new Container();

        $container->set(\Linna\Foo\DI\FooClassACache::class, new FooClassACache('Hello World'));

        $this->assertInstanceOf(
            \Linna\Foo\DI\FooClassResCache::class,
            $container->resolve(\Linna\Foo\DI\FooClassResCache::class)
        );
    }

    /**
     * Test resolving class with rules
     * for unsolvable arguments.
     */
    public function testResolveWithRules()
    {
        $container = new Container();
        $container->setRules([
            \Linna\Foo\DI\FooClassARules::class => [
                0 => true,
                2 => 'foo',
                3 => 1,
                4 => ['foo'],
                5 => 'foo',
            ],
        ]);

        $this->assertInstanceOf(
            \Linna\Foo\DI\FooClassResRules::class,
            $container->resolve(\Linna\Foo\DI\FooClassResRules::class)
        );
    }
}
