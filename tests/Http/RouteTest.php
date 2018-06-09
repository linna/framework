<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Http\Route;
use PHPUnit\Framework\TestCase;

/**
 * Route test.
 */
class RouteTest extends TestCase
{
    /**
     *
     * @var Route The route object.
     */
    public $route;

    /**
     * Setup.
     */
    public function setUp(): void
    {
        $this->route = new Route([
            'name'       => 'Home',
            'method'     => 'GET',
            'url'        => '/',
            'model'      => 'HomeModel',
            'view'       => 'HomeView',
            'controller' => 'HomeController',
            'action'     => '',
        ]);
    }

    /**
     * Test new route instance.
     */
    public function testNewRouteInstance(): void
    {
        $this->assertInstanceOf(Route::class, $this->route);
    }

    /**
     * Test get name.
     */
    public function testGetName(): void
    {
        $this->assertEquals('Home', $this->route->getName());
    }

    /**
     * Test get method.
     */
    public function testGetMethod(): void
    {
        $this->assertEquals('GET', $this->route->getMethod());
    }

    /**
     * Test get url.
     */
    public function testGetUrl(): void
    {
        $this->assertEquals('/', $this->route->getUrl());
    }

    /**
     * Test get model.
     */
    public function testGetModel(): void
    {
        $this->assertEquals('HomeModel', $this->route->getModel());
    }

    /**
     * Test get view.
     */
    public function testGetView(): void
    {
        $this->assertEquals('HomeView', $this->route->getView());
    }

    /**
     * Test get controller.
     */
    public function testGetController(): void
    {
        $this->assertEquals('HomeController', $this->route->getController());
    }

    /**
     * Test get action.
     */
    public function testGetAction(): void
    {
        $this->assertEquals('', $this->route->getAction());
    }

    /**
     * Test get param.
     */
    public function testGetParam(): void
    {
        $this->assertEquals([], $this->route->getParam());
    }

    /**
     * Test is default route.
     */
    public function testIsDefaultRoute(): void
    {
        $this->assertFalse($this->route->isDefault());
    }

    /**
     * Test get callback.
     */
    public function testGetCallback(): void
    {
        $this->assertEquals(function () {
        }, $this->route->getCallback());
    }

    /**
     * Test route to array.
     */
    public function testRouteToArray(): void
    {
        $route = [
            'name'       => 'Home',
            'method'     => 'GET',
            'url'        => '/',
            'model'      => 'HomeModel',
            'view'       => 'HomeView',
            'controller' => 'HomeController',
            'action'     => '',
            'default'    => false,
            'param'      => [],
            'callback'   => false,
        ];

        $this->assertEquals($route, $this->route->toArray());
    }
}
