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

use BadMethodCallException;
use Linna\Router\NullRoute;
use Linna\Router\Route;
use Linna\Router\RouteCollection;
use Linna\Router\Router;
use PHPUnit\Framework\TestCase;
use TypeError;

/**
 * Router Test.
 */
class RouterTest extends TestCase
{
    /**
     * @var array Routes for test.
     */
    protected static $routes;

    /**
     * @var Router The router object.
     */
    protected static $router;

    /**
     * Set up before class.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
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
            ]),
            new Route([
                'method'     => 'GET',
                'url'        => '/paramTest/[test]',
                'model'      => 'ParamModel',
                'view'       => 'ParamView',
                'controller' => 'ParamController',
            ])
        ]));

        self::$routes = $routes;

        self::$router = new Router($routes, [
            'basePath'    => '/',
            'badRoute'    => 'E404',
            'rewriteMode' => true,
        ]);
    }

    /**
     * Tear down after class.
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        self::$routes = null;
        self::$router = null;
    }

    /**
     * Wrong arguments router class provider.
     *
     * @return array
     */
    public function WrongArgumentsForRouterProvider(): array
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
     *
     * @param mixed $routes
     * @param mixed $options
     *
     * @return void
     */
    public function testNewRouterInstanceWithWrongArguments($routes, $options): void
    {
        $this->expectException(TypeError::class);

        (new Router($routes, $options));
    }

    /**
     * Wrong arguments for validate a route provider.
     *
     * @return array
     */
    public function WrongArgumentsForValidateRouteProvider(): array
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
     *
     * @param mixed $url
     * @param mixed $method
     *
     * @return void
     */
    public function testValidateRouteWithWrongArguments($url, $method): void
    {
        $this->expectException(TypeError::class);

        self::$router->validate($url, $method);
    }

    /**
     * Test get route.
     *
     * @return void
     */
    public function testGetRoute(): void
    {
        $this->assertTrue(self::$router->validate('/user', 'GET'));

        $this->assertInstanceOf(Route::class, self::$router->getRoute());
    }

    /**
     * Routes provider.
     *
     * @return array
     */
    public function routeProvider(): array
    {
        return [
            ['/user', 'POST', ['E404Model', 'E404View', 'E404Controller', null, []], false], //test not allowed http method
            ['/badroute', 'GET', ['E404Model', 'E404View', 'E404Controller', null, []], false], //test bad uri
            ['/user/5/enable', 'GET', ['UserModel', 'UserView', 'UserController', 'enable', ['id'=>'5']], true], //test param route
            ['/userOther/enable/5', 'GET', ['UserModel', 'UserView', 'UserController', 'enable', ['id'=>'5']], true], //test inverse param route
        ];
    }

    /**
     * Test routes.
     *
     * @dataProvider routeProvider
     *
     * @param string $url
     * @param string $method
     * @param array  $returneRoute
     * @param bool   $validate
     *
     * @return void
     */
    public function testRoutes(string $url, string $method, array $returneRoute, bool $validate): void
    {
        $this->assertEquals($validate, self::$router->validate($url, $method));

        $route = self::$router->getRoute();

        $this->assertInstanceOf(Route::class, $route);

        $this->assertEquals($returneRoute[0], $route->model);
        $this->assertEquals($returneRoute[1], $route->view);
        $this->assertEquals($returneRoute[2], $route->controller);
        $this->assertEquals($returneRoute[3], $route->action);
        $this->assertEquals($returneRoute[4], $route->param);
    }

    /**
     * Test routes with other base path.
     *
     * @dataProvider routeProvider
     *
     * @param string $url
     * @param string $method
     * @param array  $returneRoute
     * @param bool   $validate
     *
     * @return void
     */
    public function testRoutesWithOtherBasePath(string $url, string $method, array $returneRoute, bool $validate): void
    {
        $router = new Router(self::$routes, [
            'basePath'    => '/other_dir',
            'badRoute'    => 'E404',
            'rewriteMode' => true,
        ]);

        $this->assertEquals($validate, $router->validate('/other_dir'.$url, $method));

        $route = $router->getRoute();

        $this->assertInstanceOf(Route::class, $route);

        $this->assertEquals($returneRoute[0], $route->model);
        $this->assertEquals($returneRoute[1], $route->view);
        $this->assertEquals($returneRoute[2], $route->controller);
        $this->assertEquals($returneRoute[3], $route->action);
        $this->assertEquals($returneRoute[4], $route->param);
    }

    /**
     *  Map route provider.
     *
     * @return array
     */
    public function mapMethodRouteProvider(): array
    {
        return [
            ['GET', '/mapRouteTestGet'],
            ['POST', '/mapRouteTestPost'],
            ['PUT', '/mapRouteTestPut'],
            ['DELETE', '/mapRouteTestPatch'],
            ['PATCH', '/mapRouteTestPatch'],
        ];
    }

    /**
     * Test map route into router with map method.
     *
     * @dataProvider mapMethodRouteProvider
     *
     * @param string $method
     * @param string $url
     *
     * @return void
     */
    public function testMapInToRouterWithMapMethod(string $method, string $url): void
    {
        self::$router->map(new Route(['method' => $method, 'url' => $url]));

        $this->assertTrue(self::$router->validate($url, $method));

        $this->assertInstanceOf(Route::class, self::$router->getRoute());
    }

    /**
     * Fast map route provider.
     *
     * @return array
     */
    public function fastMapRouteProvider(): array
    {
        return [
            ['GET', '/fastMapRouteTestGet', 'get', ['name' => 'RouteGet']],
            ['POST', '/fastMapRouteTestPost', 'post', ['name' => 'RoutePost']],
            ['PUT', '/fastMapRouteTestPut', 'put', ['name' => 'RoutePut']],
            ['DELETE', '/fastMapRouteTestDelete', 'delete', ['name' => 'RouteDelete']],
            ['PATCH', '/fastMapRouteTestPatch', 'patch', ['name' => 'RoutePatch']],
        ];
    }

    /**
     * Test map route into wouter with fast map methods.
     *
     * @dataProvider fastMapRouteProvider
     *
     * @param string $method
     * @param string $url
     * @param string $func
     * @param array  $options
     *
     * @return void
     */
    public function testMapInToRouterWithFastMapRoute(string $method, string $url, string $func, array $options): void
    {
        //map route with method
        self::$router->$func($url, function ($param) {
            return $param;
        }, $options);

        $this->assertTrue(self::$router->validate($url, $method));

        $route = self::$router->getRoute();

        $callback = $route->getCallback();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals($method, $callback($method));
        $this->assertEquals($options['name'], $route->getName());
    }

    /**
     * Fast map route provider without options.
     *
     * @return array
     */
    public function fastMapRouteProviderNoOptions(): array
    {
        return [
            ['GET', '/fastMapRouteTestNoOptGet', 'get', []],
            ['POST', '/fastMapRouteTestNoOptPost', 'post', []],
            ['PUT', '/fastMapRouteTestNoOptPut', 'put', []],
            ['DELETE', '/fastMapRouteTestNoOptPatch', 'delete', []],
            ['PATCH', '/fastMapRouteTestNoOptPatch', 'patch', []],
        ];
    }

    /**
     * Test map route into wouter with fast map methods.
     *
     * @dataProvider fastMapRouteProviderNoOptions
     *
     * @param string $method
     * @param string $url
     * @param string $func
     * @param array $options
     *
     * @return void
     */
    public function testMapInToRouterWithFastMapRouteWithoutOptions(string $method, string $url, string $func, array $options): void
    {
        //map route with method
        self::$router->$func($url, function ($param) {
            return $param;
        });

        $this->assertTrue(self::$router->validate($url, $method));

        $route = self::$router->getRoute();

        $callback = $route->getCallback();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals($method, $callback($method));
        $this->assertEquals($options, []);
    }

    /**
     * Test Router bad method call.
     *
     * @return void
     */
    public function testRouterBadMethodCall(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage("Router->foo() method do not exist.");

        self::$router->foo();
    }

    /**
     * Test validate a route with no bad route options declared.
     *
     * @return void
     */
    public function testValidateRouteWithNoBadRouteDeclared(): void
    {
        //using a worng bad route for overwrite previous setting
        $router = new Router(self::$routes, [
            'badRoute'    => 'E40',
            'rewriteMode' => true,
        ]);

        $this->assertFalse($router->validate('/badroute', 'GET'));

        $this->assertInstanceOf(NullRoute::class, $router->getRoute());
    }

    /**
     * Test validate with rewrite mode off.
     *
     * @return void
     */
    public function testValidateWithRewriteModeOff(): void
    {
        $router = new Router(self::$routes, [
            'badRoute'    => 'E404',
            'rewriteMode' => false,
        ]);

        $this->assertTrue($router->validate('/index.php?/user/5/enable', 'GET'));

        $route = $router->getRoute();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('UserModel', $route->model);
        $this->assertEquals('UserView', $route->view);
        $this->assertEquals('UserController', $route->controller);
        $this->assertEquals('enable', $route->action);
        $this->assertEquals(['id'=>'5'], $route->param);
    }

    /**
     * Test validate with rewrite mode off and other base path.
     *
     * @return void
     */
    public function testValidateWithRewriteModeOffWithAndOtherBasePath(): void
    {
        $router = new Router(self::$routes, [
            'basePath'    => '/other_dir',
            'badRoute'    => 'E404',
            'rewriteMode' => false,
        ]);

        //evaluate request uri
        $this->assertTrue($router->validate('/other_dir/index.php?/user/5/enable', 'GET'));

        //get route
        $route = $router->getRoute();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('UserModel', $route->model);
        $this->assertEquals('UserView', $route->view);
        $this->assertEquals('UserController', $route->controller);
        $this->assertEquals('enable', $route->action);
        $this->assertEquals(['id'=>'5'], $route->param);
    }

    /**
     * Rest route provider.
     *
     * @return array
     */
    public function restRouteProvider(): array
    {
        return [
            ['/user/5', 'GET', 'Show'],
            ['/user/5', 'POST', 'Update'],
            ['/user/5', 'PATCH', 'Update'],
            ['/user/5', 'PUT', 'Create'],
            ['/user/5', 'DELETE', 'Delete'],
        ];
    }

    /**
     * Test rest routing.
     *
     * @dataProvider restRouteProvider
     *
     * @param string $uri
     * @param string $method
     * @param string $action
     *
     * @return void
     */
    public function testRESTRouting(string $uri, string $method, string $action): void
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
                'method'     => 'PUT',
                'url'        => '/user/[id]',
                'model'      => 'UserCreateModel',
                'view'       => 'UserCreateView',
                'controller' => 'UserCreateController',
            ]),
            new Route([
                'method'     => 'POST',
                'url'        => '/user/[id]',
                'model'      => 'UserUpdateModel',
                'view'       => 'UserUpdateView',
                'controller' => 'UserUpdateController',
            ]),
            new Route([
                'method'     => 'PATCH',
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
        ]));

        $router = new Router($restRoutes, [
            'badRoute'    => 'E404',
            'rewriteMode' => true,
        ]);

        $this->assertTrue($router->validate($uri, $method));

        //get route
        $route = $router->getRoute();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('User'.$action.'Model', $route->model);
        $this->assertEquals('User'.$action.'View', $route->view);
        $this->assertEquals('User'.$action.'Controller', $route->controller);
    }

    /**
     * Test return first matching route.
     *
     * @return void
     */
    public function testReturnFirstMatchingRoute(): void
    {
        $routes = (new RouteCollection([
            new Route([
                'name'       => 'User',
                'method'     => 'GET',
                'url'        => '/user',
                'model'      => 'UserModel',
                'view'       => 'UserView',
                'controller' => 'UserController',
            ]),
            new Route([
                'name'       => 'User',
                'method'     => 'GET',
                'url'        => '/user',
                'model'      => 'User1Model',
                'view'       => 'User1View',
                'controller' => 'User1Controller',
            ])
        ]));

        $router = new Router($routes, [
            'basePath'    => '/',
            'rewriteMode' => true,
        ]);

        $this->assertTrue($router->validate('/user', 'GET'));

        //get route
        $route = $router->getRoute();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('UserModel', $route->model);
        $this->assertEquals('UserView', $route->view);
        $this->assertEquals('UserController', $route->controller);
    }

    /**
     * Test not equal route name.
     *
     * @return void
     */
    public function testNotEqualRouteName(): void
    {
        $routes = (new RouteCollection([
            new Route([
                'name'       => 'User',
                'method'     => 'GET',
                'url'        => '/user',
                'model'      => 'UserModel',
                'view'       => 'UserView',
                'controller' => 'UserController',
            ]),
            new Route([
                'name'       => 404,
                'method'     => 'GET',
                'url'        => '/error',
                'model'      => 'ErrorModel',
                'view'       => 'ErrorView',
                'controller' => 'ErrorController',
            ])
        ]));

        $router = new Router($routes, [
            'basePath'    => '/',
            'badRoute'    => '404',
            'rewriteMode' => true,
        ]);

        $this->assertFalse($router->validate('/user/bad', 'GET'));
        $this->assertInstanceOf(NullRoute::class, $router->getRoute());
    }

    /**
     * Route with param provider.
     *
     * @return array
     */
    public function routeWithParamProvider(): array
    {
        return [
            ['/paramTest/az', 'az'],
            ['/paramTest/aZ', 'aZ'],
            ['/paramTest/a9', 'a9'],
            ['/paramTest/a9.', 'a9.'],
            ['/paramTest/a9-', 'a9-'],
            ['/paramTest/._-', '._-'],
        ];
    }

    /**
     * Test allowed chars in route param.
     *
     * @dataProvider routeWithParamProvider
     *
     * @return void
     */
    public function testAllowedCharsInRouteParam(string $uri, string $result): void
    {
        self::$router->validate($uri, 'GET');

        $route = self::$router->getRoute();

        $this->assertEquals($result, $route->param['test']);
    }

    /**
     * Route with query string provider.
     *
     * @return array
     */
    public function routeWithQueryStringProvider(): array
    {
        return [
            ['/user?id=1', 'id', '1'],
            ['/user?id= 1', 'id', '1'],
            ['/user?id=%201', 'id', ' 1'],
            ['/user?id=', 'id', ''],
            ['/user?id==', 'id', '']
        ];
    }

    /**
     * Test query strin on rewrite mode on.
     *
     * @dataProvider routeWithQueryStringProvider
     *
     * @return void
     */
    public function testQueryStringOnRewriteModeOn(string $uri, string $key, string $value): void
    {
        $routes = (new RouteCollection([
            new Route([
                'name'       => 'User',
                'method'     => 'GET',
                'url'        => '/user',
                'model'      => 'UserModel',
                'view'       => 'UserView',
                'controller' => 'UserController',
            ])
        ]));

        $router = new Router($routes, [
            'basePath'    => '/',
            'badRoute'    => '404',
            'rewriteMode' => true,
            'parseQueryStringOnRewriteModeOn' => true
        ]);

        $this->assertTrue($router->validate($uri, 'GET'));

        $route = $router->getRoute();

        $this->assertInstanceOf(Route::class, $router->getRoute());

        $params = $route->param;

        $this->assertCount(1, $params);
        $this->assertArrayHasKey($key, $params);
        $this->assertSame($params[$key], $value);
    }
}
