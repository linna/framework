<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Router;

use PHPUnit\Framework\TestCase;

/**
 * Route test.
 */
class RouteTest extends TestCase
{
    /** @var Route The route object. */
    public static Route $route;

    /**
     * Set up before class.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$route = new Route(
            name:        'Home',
            method:      'GET',
            path:         '/',
            model:       'HomeModel',
            view:        'HomeView',
            controller:  'HomeController',
            action:      'Login',
        );
    }

    /**
     * Tear down after class.
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        // self::$route = null;
    }

    /**
     * Test new route instance.
     *
     * @return void
     */
    public function testNewRouteInstance(): void
    {
        $this->assertInstanceOf(Route::class, self::$route);
    }

    /**
     * Test get name.
     *
     * @return void
     */
    public function testGetName(): void
    {
        $this->assertEquals('Home', self::$route->name);
    }

    /**
     * Test get method.
     *
     * @return void
     */
    public function testGetMethod(): void
    {
        $this->assertEquals('GET', self::$route->method);
    }

    /**
     * Test get url.
     *
     * @return void
     */
    public function testGetPath(): void
    {
        $this->assertEquals('/', self::$route->path);
    }

    /**
     * Test get model.
     *
     * @return void
     */
    public function testGetModel(): void
    {
        $this->assertEquals('HomeModel', self::$route->model);
    }

    /**
     * Test get view.
     *
     * @return void
     */
    public function testGetView(): void
    {
        $this->assertEquals('HomeView', self::$route->view);
    }

    /**
     * Test get controller.
     *
     * @return void
     */
    public function testGetController(): void
    {
        $this->assertEquals('HomeController', self::$route->controller);
    }

    /**
     * Test get action.
     *
     * @return void
     */
    public function testGetAction(): void
    {
        $this->assertEquals('Login', self::$route->action);
    }

    /**
     * Test get param.
     *
     * @return void
     */
    /*public function testGetParam(): void
    {
        $this->assertEquals([], self::$route->getParam());
    }*/

    /**
     * Test is default route.
     *
     * @return void
     */
    /*public function testIsDefaultRoute(): void
    {
        $this->assertFalse(self::$route->isDefault());
    }*/

    /**
     * Test get callback.
     *
     * @return void
     */
    /*public function testGetCallback(): void
    {
        $this->assertEquals(function () {
        }, self::$route->getCallback());
    }*/

    /**
     * Test route to array.
     *
     * @return void
     */
    /*public function testRouteToArray(): void
    {
        $route = [
            'name'       => 'Home',
            'method'     => 'GET',
            'url'        => '/',
            'model'      => 'HomeModel',
            'view'       => 'HomeView',
            'controller' => 'HomeController',
            'action'     => 'Login',
            'default'    => false,
            'param'      => [],
            'callback'   => false,
        ];

        $this->assertEquals($route, self::$route->toArray());
    }*/
}
