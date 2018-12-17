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
     */
    public function testCreateInstance(): void
    {
        $this->assertInstanceOf(RouteCollection::class, (new RouteCollection()));
    }

    /**
     * Test new instance passing right typed array to constructor.
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
     */
    public function testAssignWrongTypedValueToArray(): void
    {
        $routes = new RouteCollection();
        $routes[] = new SplStack();
    }

    /**
     * Test route to array.
     */
    /*public function testRouteCollectionToArray(): void
    {
        $routes = new RouteCollection([
            new Route([
                'name'       => 'Home',
                'method'     => 'GET',
                'url'        => '/',
            ])
        ]);

        $this->assertEquals([[
            'name'       => 'Home',
            'method'     => 'GET',
            'url'        => '/',
            'model'      => '',
            'view'       => '',
            'controller' => '',
            'action'     => '',
            'default'    => false,
            'param'      => [],
            'callback'   => false,
        ]], $routes->toArray());
    }*/
}
