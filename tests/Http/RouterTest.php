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
use Linna\Http\RouteCollection;
use Linna\Http\Router;
use PHPUnit\Framework\TestCase;

/**
 * Router Test.
 */
class RouterTest extends TestCase
{
    /**
     * @var array Routes for test.
     */
    protected $routes;

    /**
     * @var Router The router object.
     */
    protected $router;

    /**
     * Setup.
     */
    public function setUp()
    {
        $routes = (new RouteCollection([
            new Route([
                'name'       => 'Home',
                'method'     => 'GET',
                'url'        => '/',
                'model'      => 'HomeModel',
                'view'       => 'HomeView',
                'controller' => 'HomeController',
            ]),
            new Route([
                'name'       => 'E404',
                'method'     => 'GET',
                'url'        => '/error',
                'model'      => 'E404Model',
                'view'       => 'E404View',
                'controller' => 'E404Controller',
            ]),
            new Route([
                'name'       => 'User',
                'method'     => 'GET',
                'url'        => '/user',
                'model'      => 'UserModel',
                'view'       => 'UserView',
                'controller' => 'UserController',
            ]),
            new Route([
                'method'     => 'GET',
                'url'        => '/user/[id]/(disable|enable|delete|changePassword|modify)',
                'model'      => 'UserModel',
                'view'       => 'UserView',
                'controller' => 'UserController',
            ]),
            new Route([
                'method'     => 'GET',
                'url'        => '/userOther/(disable|enable|delete|changePassword|modify)/[id]',
                'model'      => 'UserModel',
                'view'       => 'UserView',
                'controller' => 'UserController',
            ])
        ]))->toArray();

        $this->routes = $routes;

        //start router
        $this->router = new Router($routes, [
            'basePath'    => '/',
            'badRoute'    => 'E404',
            'rewriteMode' => true,
        ]);
    }

    /**
     * Wrong arguments router class provider.
     *
     * @return array
     */
    public function WrongArgumentsForRouterProvider() : array
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
     * Test new router instance with wrong arguments.
     *
     * @dataProvider WrongArgumentsForRouterProvider
     * @expectedException TypeError
     */
    public function testNewRouterInstanceWithWrongArguments($routes, $options)
    {
        return new Router($routes, $options);
    }

    /**
     * Wrong arguments for validate a route provider.
     *
     * @return array
     */
    public function WrongArgumentsForValidateRouteProvider() : array
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
     * Test validate route with worng arguments.
     *
     * @dataProvider WrongArgumentsForValidateRouteProvider
     * @expectedException TypeError
     */
    public function testValidateRouteWithWrongArguments($url, $method)
    {
        $this->router->validate($url, $method);
    }

    /**
     * Test get route.
     */
    public function testGetRoute()
    {
        $this->router->validate('/user', 'GET');

        $this->assertInstanceOf(Route::class, $this->router->getRoute());
    }

    /**
     * Routes provider.
     *
     * @return array
     */
    public function routeProvider() : array
    {
        return [
            ['/user', 'POST', ['E404Model', 'E404View', 'E404Controller', null, []]], //test not allowed http method
            ['/badroute', 'GET', ['E404Model', 'E404View', 'E404Controller', null, []]], //test bad uri
            ['/user/5/enable', 'GET', ['UserModel', 'UserView', 'UserController', 'enable', ['id'=>'5']]], //test param route
            ['/userOther/enable/5', 'GET', ['UserModel', 'UserView', 'UserController', 'enable', ['id'=>'5']]], //test inverse param route
        ];
    }

    /**
     * Test routes.
     *
     * @dataProvider routeProvider
     */
    public function testRoutes($url, $method, $returneRoute)
    {
        $this->router->validate($url, $method);

        $route = $this->router->getRoute();

        $array = $route->toArray();

        $this->assertInstanceOf(Route::class, $route);
        
        $this->assertEquals($returneRoute[0], $array['model']);
        $this->assertEquals($returneRoute[1], $array['view']);
        $this->assertEquals($returneRoute[2], $array['controller']);
        $this->assertEquals($returneRoute[3], $array['action']);
        $this->assertEquals($returneRoute[4], $array['param']);
    }

    /**
     * Routes with other base path provider.
     *
     * @return array
     */
    public function routesWithOtherBasePathProvider() : array
    {
        return [
            ['/other_dir/user', 'POST', ['E404Model', 'E404View', 'E404Controller', null, []]], //test not allowed http method
            ['/other_dir/badroute', 'GET', ['E404Model', 'E404View', 'E404Controller', null, []]], //test bad uri
            ['/other_dir/user/5/enable', 'GET', ['UserModel', 'UserView', 'UserController', 'enable', ['id'=>'5']]], //test param route
            ['/other_dir/userOther/enable/5', 'GET', ['UserModel', 'UserView', 'UserController', 'enable', ['id'=>'5']]], //test inverse param route
        ];
    }

    /**
     * Test routes with other base path.
     *
     * @dataProvider routesWithOtherBasePathProvider
     */
    public function testRoutesWithOtherBasePath($url, $method, $returneRoute)
    {
        $this->router->setOption('basePath', '/other_dir');

        $this->router->validate($url, $method);

        $route = $this->router->getRoute();

        $array = $route->toArray();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals($returneRoute[0], $array['model']);
        $this->assertEquals($returneRoute[1], $array['view']);
        $this->assertEquals($returneRoute[2], $array['controller']);
        $this->assertEquals($returneRoute[3], $array['action']);
        $this->assertEquals($returneRoute[4], $array['param']);
    }

    /**
     * Fast map route provider.
     *
     * @return array
     */
    public function fastMapRouteProvider() : array
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
     * Test map route into router with map method.
     *
     * @dataProvider fastMapRouteProvider
     */
    public function testMapInToRouterWithMapMethod($method, $url, $func)
    {
        $this->router->map(['method' => $method, 'url' => $url]);

        $this->router->validate($url, $method);

        $this->assertInstanceOf(Route::class, $this->router->getRoute());
    }

    /**
     * Test map route into wouter with fast map methods.
     *
     * @dataProvider fastMapRouteProvider
     */
    public function testMapInToRouterWithFastMapRoute($method, $url, $func)
    {
        //map route with method
        $this->router->$func($url, function ($param) {
            return $param;
        });

        $this->router->validate($url, $method);

        $route = $this->router->getRoute();

        $callback = $route->getCallback();
        
        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals($method, $callback($method));
    }

    /**
     * Test validate a route with no bad route options declared.
     */
    public function testValidateRouteWithNoBadRouteDeclared()
    {
        //using a worng bad route for overwrite previous setting
        $this->router->setOptions([
            'badRoute'    => 'E40',
            'rewriteMode' => true,
        ]);

        $this->router->validate('/badroute', 'GET');

        $this->assertInstanceOf(NullRoute::class, $this->router->getRoute());
    }

    /**
     * Test validate with rewrite mode off.
     */
    public function testValidateWithRewriteModeOff()
    {
        $this->router->setOptions([
            'badRoute'    => 'E404',
            'rewriteMode' => false,
        ]);

        $this->router->validate('/index.php?/user/5/enable', 'GET');

        $route = $this->router->getRoute();

        $array = $route->toArray();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('UserModel', $array['model']);
        $this->assertEquals('UserView', $array['view']);
        $this->assertEquals('UserController', $array['controller']);
        $this->assertEquals('enable', $array['action']);
        $this->assertEquals(['id'=>'5'], $array['param']);
    }

    /**
     * Test validate with rewrite mode off and other base path.
     */
    public function testValidateWithRewriteModeOffWithAndOtherBasePath()
    {
        $this->router->setOptions([
            'basePath'    => '/other_dir',
            'badRoute'    => 'E404',
            'rewriteMode' => false,
        ]);

        //evaluate request uri
        $this->router->validate('/other_dir/index.php?/user/5/enable', 'GET');

        //get route
        $route = $this->router->getRoute();

        $array = $route->toArray();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('UserModel', $array['model']);
        $this->assertEquals('UserView', $array['view']);
        $this->assertEquals('UserController', $array['controller']);
        $this->assertEquals('enable', $array['action']);
        $this->assertEquals(['id'=>'5'], $array['param']);
    }

    /**
     * Rest route provider.
     *
     * @return array
     */
    public function restRouteProvider() : array
    {
        return [
            ['/user/5', 'GET', 'Show'],
            ['/user/5', 'POST', 'Create'],
            ['/user/5', 'PUT', 'Update'],
            ['/user/5', 'DELETE', 'Delete'],
        ];
    }

    /**
     * Test rest routing.
     *
     * @dataProvider restRouteProvider
     */
    public function testRESTRouting($uri, $method, $action)
    {
        $restRoutes = (new RouteCollection([
            new Route([
                'method'     => 'GET',
                'url'        => '/user/[id]',
                'model'      => 'UserShowModel',
                'view'       => 'UserShowView',
                'controller' => 'UserShowController',
            ]),
            new Route([
                'method'     => 'POST',
                'url'        => '/user/[id]',
                'model'      => 'UserCreateModel',
                'view'       => 'UserCreateView',
                'controller' => 'UserCreateController',
            ]),
            new Route([
                'method'     => 'PUT',
                'url'        => '/user/[id]',
                'model'      => 'UserUpdateModel',
                'view'       => 'UserUpdateView',
                'controller' => 'UserUpdateController',
            ]),
            new Route([
                'method'     => 'PUT',
                'url'        => '/user/[id]',
                'model'      => 'UserUpdateModel',
                'view'       => 'UserUpdateView',
                'controller' => 'UserUpdateController',
            ]),
            new Route([
                'method'     => 'DELETE',
                'url'        => '/user/[id]',
                'model'      => 'UserDeleteModel',
                'view'       => 'UserDeleteView',
                'controller' => 'UserDeleteController',
            ])
        ]))->toArray();

        $router = new Router($restRoutes, [
            'badRoute'    => 'E404',
            'rewriteMode' => true,
        ]);

        $router->validate($uri, $method);

        //get route
        $route = $router->getRoute();

        $array = $route->toArray();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('User'.$action.'Model', $array['model']);
        $this->assertEquals('User'.$action.'View', $array['view']);
        $this->assertEquals('User'.$action.'Controller', $array['controller']);
    }
}
