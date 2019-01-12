<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Tests;

use ArrayObject;
use InvalidArgumentException;
use Linna\Router\Route;
use Linna\Router\RouteCollection;
use Linna\TypedObjectArray;
use PHPUnit\Framework\TestCase;
use SplStack;

/**
 * Route Collection test.
 */
class RouteCollectionTest extends TestCase
{
    /**
     * Test new instance.
     *
     * @return void
     */
    public function testCreateInstance(): void
    {
        $this->assertInstanceOf(RouteCollection::class, (new RouteCollection()));
    }

    /**
     * Test new instance passing right typed array to constructor.
     *
     * @return void
     */
    public function testCreateInstanceWithRightTypedArray(): void
    {
        $this->assertInstanceOf(
            RouteCollection::class,
            (new RouteCollection([
                new Route([
                    'name'       => 'Home',
                    'method'     => 'GET',
                    'url'        => '/',
                ]),
                new Route([
                    'name'       => 'E404',
                    'method'     => 'GET',
                    'url'        => '/error',
                ])
            ]))
        );
    }

    /**
     * Test new instance passing array with invalid element to constructor.
     *
     * @expectedException InvalidArgumentException
     *
     * @return void
     */
    public function testCreateInstanceWithWrongTypedArray(): void
    {
        $this->assertInstanceOf(
            TypedObjectArray::class,
            (
                new RouteCollection([
                    new ArrayObject([1, 2, 3]),
                    new ArrayObject([1.1, 2.2, 3.3]),
                    new SplStack(),
                ])
            )
        );
    }

    /**
     * Test assign to array a right typed value.
     *
     * @return void
     */
    public function testAssignrRightTypedValueToArray(): void
    {
        $routes = new RouteCollection();
        $routes[] = new Route([
            'name'       => 'E404',
            'method'     => 'GET',
            'url'        => '/error',
        ]);

        $this->assertEquals(1, $routes->count());
    }

    /**
     * Test assign to array a wrong typed value.
     *
     * @expectedException InvalidArgumentException
     *
     * @return void
     */
    public function testAssignWrongTypedValueToArray(): void
    {
        $routes = new RouteCollection();
        $routes[] = new SplStack();
    }
}
