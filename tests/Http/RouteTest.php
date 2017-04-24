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

class RouteTest extends TestCase
{
    public $route;

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

    public function testCreateRoute()
    {
        $this->assertInstanceOf(Route::class, $this->route);
    }

    public function testGetName()
    {
        $this->assertEquals('Home', $this->route->getName());
    }

    public function testGetMethod()
    {
        $this->assertEquals('GET', $this->route->getMethod());
    }

    public function testGetUrl()
    {
        $this->assertEquals('/', $this->route->getUrl());
    }

    public function testGetModel()
    {
        $this->assertEquals('HomeModel', $this->route->getModel());
    }

    public function testGetView()
    {
        $this->assertEquals('HomeView', $this->route->getView());
    }

    public function testGetController()
    {
        $this->assertEquals('HomeController', $this->route->getController());
    }

    public function testGetAction()
    {
        $this->assertEquals('', $this->route->getAction());
    }

    public function testGetParam()
    {
        $this->assertEquals([], $this->route->getParam());
    }

    public function testIsDefault()
    {
        $this->assertEquals(false, $this->route->isDefault());
    }

    public function testGetCallback()
    {
        $this->assertEquals(function () {
        }, $this->route->getCallback());
    }

    public function testToArray()
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
