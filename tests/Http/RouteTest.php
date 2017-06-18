<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
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
    public function setUp()
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
    public function testNewRouteInstance()
    {
        $this->assertInstanceOf(Route::class, $this->route);
    }

    /**
     * Test get name.
     */
    public function testGetName()
    {
        $this->assertEquals('Home', $this->route->getName());
    }

    /**
     * Test get method.
     */
    public function testGetMethod()
    {
        $this->assertEquals('GET', $this->route->getMethod());
    }

    /**
     * Test get url.
     */
    public function testGetUrl()
    {
        $this->assertEquals('/', $this->route->getUrl());
    }

    /**
     * Test get model.
     */
    public function testGetModel()
    {
        $this->assertEquals('HomeModel', $this->route->getModel());
    }

    /**
     * Test get view.
     */
    public function testGetView()
    {
        $this->assertEquals('HomeView', $this->route->getView());
    }

    /**
     * Test get controller.
     */
    public function testGetController()
    {
        $this->assertEquals('HomeController', $this->route->getController());
    }

    /**
     * Test get action.
     */
    public function testGetAction()
    {
        $this->assertEquals('', $this->route->getAction());
    }

    /**
     * Test get param.
     */
    public function testGetParam()
    {
        $this->assertEquals([], $this->route->getParam());
    }

    /**
     * Test is default route.
     */
    public function testIsDefaultRoute()
    {
        $this->assertEquals(false, $this->route->isDefault());
    }

    /**
     * Test get callback.
     */
    public function testGetCallback()
    {
        $this->assertEquals(function () {
        }, $this->route->getCallback());
    }

    /**
     * Test route to array.
     */
    public function testRouteToArray()
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
