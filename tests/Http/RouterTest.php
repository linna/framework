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
use Linna\Http\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    protected $router;

    public function setUp()
    {
        $routes = [
            [
                'name'       => 'Home',
                'method'     => 'GET',
                'url'        => '/',
                'model'      => 'HomeModel',
                'view'       => 'HomeView',
                'controller' => 'HomeController',
                'action'     => '',
            ],
            [
                'name'       => 'E404',
                'method'     => 'GET',
                'url'        => '/error',
                'model'      => 'E404Model',
                'view'       => 'E404View',
                'controller' => 'E404Controller',
                'action'     => '',
            ],
            [
                'name'       => 'User',
                'method'     => 'GET',
                'url'        => '/user',
                'model'      => 'UserModel',
                'view'       => 'UserView',
                'controller' => 'UserController',
                'action'     => '',
            ],
            [
                'name'       => '',
                'method'     => 'GET',
                'url'        => '/user/[id]/(disable|enable|delete|changePassword|modify)',
                'model'      => 'UserModel',
                'view'       => 'UserView',
                'controller' => 'UserController',
                'action'     => '',
            ],
            [
                'name'       => '',
                'method'     => 'GET',
                'url'        => '/userOther/(disable|enable|delete|changePassword|modify)/[id]',
                'model'      => 'UserModel',
                'view'       => 'UserView',
                'controller' => 'UserController',
                'action'     => '',
            ],
        ];

        //start router
        $this->router = new Router($routes, [
            'basePath'    => '/',
            'badRoute'    => 'E404',
            'rewriteMode' => true,
        ]);
    }

    public function testRoute()
    {
        //evaluate request uri
        $this->router->validate('/user', 'GET');

        //get route
        $route = $this->router->getRoute();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('UserModel', $route->getModel());
        $this->assertEquals('UserView', $route->getView());
        $this->assertEquals('UserController', $route->getController());
        $this->assertEquals(null, $route->getAction());
        $this->assertEquals([], $route->getParam());
    }

    public function testBadMethod()
    {
        //evaluate request uri
        $this->router->validate('/user', 'POST');

        //get route
        $route = $this->router->getRoute();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('E404Model', $route->getModel());
        $this->assertEquals('E404View', $route->getView());
        $this->assertEquals('E404Controller', $route->getController());
        $this->assertEquals(null, $route->getAction());
        $this->assertEquals([], $route->getParam());
    }

    public function testBadRoute()
    {
        //evaluate request uri
        $this->router->validate('/badroute', 'GET');

        //get route
        $route = $this->router->getRoute();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('E404Model', $route->getModel());
        $this->assertEquals('E404View', $route->getView());
        $this->assertEquals('E404Controller', $route->getController());
        $this->assertEquals(null, $route->getAction());
        $this->assertEquals([], $route->getParam());
    }

    public function testParamRoute()
    {
        //evaluate request uri
        $this->router->validate('/user/5/enable', 'GET');

        //get route
        $route = $this->router->getRoute();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('UserModel', $route->getModel());
        $this->assertEquals('UserView', $route->getView());
        $this->assertEquals('UserController', $route->getController());
        $this->assertEquals('enable', $route->getAction());
        $this->assertEquals(['id'=>'5'], $route->getParam());
    }

    public function testInverseParamRoute()
    {
        //evaluate request uri
        $this->router->validate('/userOther/enable/5', 'GET');

        //get route
        $route = $this->router->getRoute();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('UserModel', $route->getModel());
        $this->assertEquals('UserView', $route->getView());
        $this->assertEquals('UserController', $route->getController());
        $this->assertEquals('enable', $route->getAction());
        $this->assertEquals(['id'=>'5'], $route->getParam());
    }

    public function RouteProvider()
    {
        return [
            ['GET', '/mapRouteTestGet', 'get'],
            ['POST', '/mapRouteTestPost', 'post'],
            ['PUT', '/mapRouteTestPut', 'put'],
            ['DELETE', '/mapRouteTestPatch', 'delete'],
            ['PATCH', '/mapRouteTestPatch', 'patch'],
        ];
    }

    /**
     * @dataProvider RouteProvider
     */
    public function testMapRoute($method, $url, $func)
    {
        $route = ['method' => $method, 'url' => $url];

        $this->router->map($route);

        $this->router->validate($url, $method);

        $this->assertInstanceOf(Route::class, $this->router->getRoute());
    }

    /**
     * @dataProvider RouteProvider
     */
    public function testFastMapRoute($method, $url, $func)
    {
        $this->router->$func($url, function ($param) {
            return $param;
        });

        $this->router->validate($url, $method);

        $route = $this->router->getRoute();

        $this->assertInstanceOf(Route::class, $route);

        $callback = $route->getCallback();

        $this->assertEquals($method, $callback($method));
    }

    public function testNoBadRouteDeclared()
    {
        $this->router->setOptions([
            'basePath'    => '/',
            'badRoute'    => 'E40',
            'rewriteMode' => true,
        ]);

        //evaluate request uri
        $this->router->validate('/badroute', 'GET');

        $this->assertEquals(false, $this->router->getRoute());
    }

    public function testRewriteModeOff()
    {
        $this->router->setOptions([
            'basePath'    => '/',
            'badRoute'    => 'E404',
            'rewriteMode' => false,
        ]);

        //evaluate request uri
        $this->router->validate('/index.php/user/5/enable', 'GET');

        //get route
        $route = $this->router->getRoute();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('UserModel', $route->getModel());
        $this->assertEquals('UserView', $route->getView());
        $this->assertEquals('UserController', $route->getController());
        $this->assertEquals('enable', $route->getAction());
        $this->assertEquals(['id'=>'5'], $route->getParam());
    }
}
