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

use Linna\Router\NullRoute;
use Linna\Router\Route;
use Linna\Router\RouteCollection;
use Linna\Router\Router;
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
    public function setUp(): void
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
        ]))->getArrayCopy();

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
     * @expectedException TypeError
     */
    public function testNewRouterInstanceWithWrongArguments($routes, $options): void
    {
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
     * @expectedException TypeError
     */
    public function testValidateRouteWithWrongArguments($url, $method): void
    {
        $this->router->validate($url, $method);
    }

    /**
     * Test get route.
     */
    public function testGetRoute(): void
    {
        $this->assertTrue($this->router->validate('/user', 'GET'));

        $this->assertInstanceOf(Route::class, $this->router->getRoute());
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
     */
    public function testRoutes(string $url, string $method, array $returneRoute, bool $validate): void
    {
        $this->assertEquals($validate, $this->router->validate($url, $method));

        $route = $this->router->getRoute();

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
     */
    public function testRoutesWithOtherBasePath(string $url, string $method, array $returneRoute, bool $validate): void
    {
        $router = new Router($this->routes, [
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
     */
    public function testMapInToRouterWithMapMethod(string $method, string $url): void
    {
        $this->router->map(new Route(['method' => $method, 'url' => $url]));

        $this->assertTrue($this->router->validate($url, $method));

        $this->assertInstanceOf(Route::class, $this->router->getRoute());
    }

    /**
     * Fast map route provider.
     *
     * @return array
     */
    public function fastMapRouteProvider(): array
    {
        return [
            ['GET', '/mapRouteTestGet', 'get', ['name' => 'RouteGet']],
            ['POST', '/mapRouteTestPost', 'post', ['name' => 'RoutePost']],
            ['PUT', '/mapRouteTestPut', 'put', ['name' => 'RoutePut']],
            ['DELETE', '/mapRouteTestDelete', 'delete', ['name' => 'RouteDelete']],
            ['PATCH', '/mapRouteTestPatch', 'patch', ['name' => 'RoutePatch']],
        ];
    }

    /**
     * Test map route into wouter with fast map methods.
     *
     * @dataProvider fastMapRouteProvider
     */
    public function testMapInToRouterWithFastMapRoute(string $method, string $url, string $func, array $options): void
    {
        //map route with method
        $this->router->$func($url, function ($param) {
            return $param;
        }, $options);

        $this->assertTrue($this->router->validate($url, $method));

        $route = $this->router->getRoute();

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
            ['GET', '/mapRouteTestGet', 'get', []],
            ['POST', '/mapRouteTestPost', 'post', []],
            ['PUT', '/mapRouteTestPut', 'put', []],
            ['DELETE', '/mapRouteTestPatch', 'delete', []],
            ['PATCH', '/mapRouteTestPatch', 'patch', []],
        ];
    }

    /**
     * Test map route into wouter with fast map methods.
     *
     * @dataProvider fastMapRouteProviderNoOptions
     */
    public function testMapInToRouterWithFastMapRouteWithoutOptions(string $method, string $url, string $func, array $options): void
    {
        //map route with method
        $this->router->$func($url, function ($param) {
            return $param;
        });

        $this->assertTrue($this->router->validate($url, $method));

        $route = $this->router->getRoute();

        $callback = $route->getCallback();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals($method, $callback($method));
        $this->assertEquals($options, []);
    }

    /**
     * Test Router bad method call
     *
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage Router->foo() method do not exist.
     */
    public function testRouterBadMethodCall(): void
    {
        $this->router->foo();
    }

    /**
     * Test validate a route with no bad route options declared.
     */
    public function testValidateRouteWithNoBadRouteDeclared(): void
    {
        //using a worng bad route for overwrite previous setting
        $router = new Router($this->routes, [
            'badRoute'    => 'E40',
            'rewriteMode' => true,
        ]);

        $this->assertFalse($router->validate('/badroute', 'GET'));

        $this->assertInstanceOf(NullRoute::class, $router->getRoute());
    }

    /**
     * Test validate with rewrite mode off.
     */
    public function testValidateWithRewriteModeOff(): void
    {
        $router = new Router($this->routes, [
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
     */
    public function testValidateWithRewriteModeOffWithAndOtherBasePath(): void
    {
        $router = new Router($this->routes, [
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
     */
    public function testRESTRouting($uri, $method, $action): void
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
        ]))->getArrayCopy();

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
     * Test return first matching route
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
        ]))->getArrayCopy();

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
     * Test return first matching route
     */
    public function testEqualRouteName(): void
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
        ]))->getArrayCopy();

        $router = new Router($routes, [
            'basePath'    => '/',
            'badRoute'    => '404',
            'rewriteMode' => true,
        ]);

        $this->assertFalse($router->validate('/user/bad', 'GET'));
        $this->assertInstanceOf(NullRoute::class, $router->getRoute());
    }
}
