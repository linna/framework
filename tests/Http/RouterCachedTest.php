<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Cache\DiskCache;
use Linna\Http\Route;
use Linna\Http\RouterCached;
use PHPUnit\Framework\TestCase;

class RouterCachedTest extends TestCase
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
        $this->router = new RouterCached(new DiskCache(), $routes, [
            'basePath'    => '/',
            'badRoute'    => 'E404',
            'rewriteMode' => true,
        ]);
    }

    public function testCachedRoute()
    {
        //evaluate request uri
        $this->router->validate('/user', 'GET');
        $this->assertInstanceOf(Route::class, $this->router->getRoute());

        //evaluate request uri, now from cache
        $this->router->validate('/user', 'GET');
        $this->assertInstanceOf(Route::class, $this->router->getRoute());

        //evaluate request uri, now from cache
        $this->router->validate('/user', 'GET');
        $this->assertInstanceOf(Route::class, $this->router->getRoute());
    }

    public function routesProvider()
    {
        return [
            ['/user', 'POST', ['E404Model', 'E404View', 'E404Controller', null, []]], //test not allowed http method
            ['/badroute', 'GET', ['E404Model', 'E404View', 'E404Controller', null, []]], //test bad uri
            ['/user/5/enable', 'GET', ['UserModel', 'UserView', 'UserController', 'enable', ['id'=>'5']]], //test param route
            ['/userOther/enable/5', 'GET', ['UserModel', 'UserView', 'UserController', 'enable', ['id'=>'5']]], //test inverse param route
        ];
    }

    /**
     * @dataProvider routesProvider
     */
    public function testRoutes($url, $method, $returneRoute)
    {
        //evaluate request uri
        $this->router->validate($url, $method);

        //get route
        $route = $this->router->getRoute();

        $arrayRoute = $route->toArray();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals($returneRoute[0], $arrayRoute['model']);
        $this->assertEquals($returneRoute[1], $arrayRoute['view']);
        $this->assertEquals($returneRoute[2], $arrayRoute['controller']);
        $this->assertEquals($returneRoute[3], $arrayRoute['action']);
        $this->assertEquals($returneRoute[4], $arrayRoute['param']);
    }

    public function testRewriteModeOff()
    {
        //routes
        $routes = [];
        $routes[] = [
            'name'       => '',
            'method'     => 'GET',
            'url'        => '/user/[id]/(disable|enable|delete|changePassword|modify)',
            'model'      => 'UserModel',
            'view'       => 'UserView',
            'controller' => 'UserController',
            'action'     => '',
        ];

        //start router
        $router = new RouterCached(new DiskCache(), $routes, [
            'basePath'    => '/',
            'badRoute'    => 'E404',
            'rewriteMode' => false,
        ]);

        //evaluate request uri
        $router->validate('/index.php/user/5/enable', 'GET');

        //get route
        $route = $router->getRoute();

        $arrayRoute = $route->toArray();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('UserModel', $arrayRoute['model']);
        $this->assertEquals('UserView', $arrayRoute['view']);
        $this->assertEquals('UserController', $arrayRoute['controller']);
        $this->assertEquals('enable', $arrayRoute['action']);
        $this->assertEquals(['id'=>'5'], $arrayRoute['param']);
    }
}
