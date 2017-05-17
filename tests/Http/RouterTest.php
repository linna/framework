<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Http\NullRoute;
use Linna\Http\Route;
use Linna\Http\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    protected $routes;
    
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
        
        $this->routes = $routes;
        
        //start router
        $this->router = new Router($routes, [
            'basePath'    => '/',
            'badRoute'    => 'E404',
            'rewriteMode' => true,
        ]);
    }

    public function BadRouterArgumentsProvider()
    {
        return [
            [null, null],
            [true, false],
            [1, 1],
            [1.1, 1.1],
            ['foo', 'foo'],
            [(object) [1], (object) [1]],
            [function () {
            }, function () {
            }],
        ];
    }

    /**
     * @dataProvider BadRouterArgumentsProvider
     * @expectedException TypeError
     */
    public function testCreateRouterInstanceWithBadArguments($routes, $options)
    {
        return new Router($routes, $options);
    }

    public function BadRouteArgumentsProvider()
    {
        return [
            [null, null],
            [true, false],
            [1, 1],
            [1.1, 1.1],
            [[1], [1]],
            [(object) [1], (object) [1]],
            [function () {
            }, function () {
            }],
        ];
    }

    /**
     * @dataProvider BadRouteArgumentsProvider
     * @expectedException TypeError
     */
    public function testValidateWithBadArguments($url, $method)
    {
        $this->router->validate($url, $method);
    }

    public function testGetRoute()
    {
        //evaluate request uri
        $this->router->validate('/user', 'GET');

        //get route
        $route = $this->router->getRoute();

        $this->assertInstanceOf(Route::class, $route);
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
    
    public function routesWithOtherBasePathProvider()
    {
        return [
            ['/otherDir/user', 'POST', ['E404Model', 'E404View', 'E404Controller', null, []]], //test not allowed http method
            ['/otherDir/badroute', 'GET', ['E404Model', 'E404View', 'E404Controller', null, []]], //test bad uri
            ['/otherDir/user/5/enable', 'GET', ['UserModel', 'UserView', 'UserController', 'enable', ['id'=>'5']]], //test param route
            ['/otherDir/userOther/enable/5', 'GET', ['UserModel', 'UserView', 'UserController', 'enable', ['id'=>'5']]], //test inverse param route
        ];
    }
    
    /**
     * @dataProvider routesWithOtherBasePathProvider
     */
    public function testRoutesWithOtherBasePath($url, $method, $returneRoute)
    {
        $this->router->setOption('basePath' , '/otherDir');
        
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

        $this->assertInstanceOf(NullRoute::class, $this->router->getRoute());
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

        $arrayRoute = $route->toArray();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('UserModel', $arrayRoute['model']);
        $this->assertEquals('UserView', $arrayRoute['view']);
        $this->assertEquals('UserController', $arrayRoute['controller']);
        $this->assertEquals('enable', $arrayRoute['action']);
        $this->assertEquals(['id'=>'5'], $arrayRoute['param']);
    }
    
    public function testRewriteModeOffWithOtherBasePath()
    {
        $this->router->setOptions([
            'basePath'    => '/otherDir',
            'badRoute'    => 'E404',
            'rewriteMode' => false,
        ]);

        //evaluate request uri
        $this->router->validate('/otherDir/index.php/user/5/enable', 'GET');

        //get route
        $route = $this->router->getRoute();

        $arrayRoute = $route->toArray();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('UserModel', $arrayRoute['model']);
        $this->assertEquals('UserView', $arrayRoute['view']);
        $this->assertEquals('UserController', $arrayRoute['controller']);
        $this->assertEquals('enable', $arrayRoute['action']);
        $this->assertEquals(['id'=>'5'], $arrayRoute['param']);
    }
    
    public function restRouteProvider()
    {
        return [
            ['/user/5', 'GET', 'Show'],
            ['/user/5', 'POST', 'Create'],
            ['/user/5', 'PUT', 'Update'],
            ['/user/5', 'DELETE', 'Delete'],
        ];
    }

    /**
     * @dataProvider restRouteProvider
     */
    public function testRESTRouting($uri, $method, $action)
    {
        $restRoutes = [
            [
                'name'       => '',
                'method'     => 'GET',
                'url'        => '/user/[id]',
                'model'      => 'UserShowModel',
                'view'       => 'UserShowView',
                'controller' => 'UserShowController',
                'action'     => '',
            ],
            [
                'name'       => '',
                'method'     => 'POST',
                'url'        => '/user/[id]',
                'model'      => 'UserCreateModel',
                'view'       => 'UserCreateView',
                'controller' => 'UserCreateController',
                'action'     => '',
            ],
            [
                'name'       => '',
                'method'     => 'PUT',
                'url'        => '/user/[id]',
                'model'      => 'UserUpdateModel',
                'view'       => 'UserUpdateView',
                'controller' => 'UserUpdateController',
                'action'     => '',
            ],
            [
                'name'       => '',
                'method'     => 'DELETE',
                'url'        => '/user/[id]',
                'model'      => 'UserDeleteModel',
                'view'       => 'UserDeleteView',
                'controller' => 'UserDeleteController',
                'action'     => '',
            ],
        ];

        $router = new Router($restRoutes, [
            'basePath'    => '/',
            'badRoute'    => 'E404',
            'rewriteMode' => true,
        ]);

        $router->validate($uri, $method);

        //get route
        $route = $router->getRoute();

        $arrayRoute = $route->toArray();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('User'.$action.'Model', $arrayRoute['model']);
        $this->assertEquals('User'.$action.'View', $arrayRoute['view']);
        $this->assertEquals('User'.$action.'Controller', $arrayRoute['controller']);
    }
}
