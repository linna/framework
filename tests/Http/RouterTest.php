<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

declare(strict_types=1);

use Linna\Http\Router;
use Linna\Http\Route;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    protected $router;
    
    public function setUp()
    {
        $routes = [];

        $routes[] = [
            'name' => 'Home',
            'method' => 'GET',
            'url' => '/',
            'model' => 'HomeModel',
            'view' => 'HomeView',
            'controller' => 'HomeController',
            'action' => '',
        ];

        $routes[] = [
            'name' => 'E404',
            'method' => 'GET',
            'url' => '/error',
            'model' => 'E404Model',
            'view' => 'E404View',
            'controller' => 'E404Controller',
            'action' => '',
        ];

        $routes[] = [
            'name' => 'User',
            'method' => 'GET',
            'url' => '/user',
            'model' => 'UserModel',
            'view' => 'UserView',
            'controller' => 'UserController',
            'action' => '',
        ];
        
        $routes[] = [
            'name' => '',
            'method' => 'GET',
            'url' => '/user/[id]/(disable|enable|delete|changePassword|modify)',
            'model' => 'UserModel',
            'view' => 'UserView',
            'controller' => 'UserController',
            'action' => '',
        ];
        
        $routes[] = [
            'name' => '',
            'method' => 'GET',
            'url' => '/userOther/(disable|enable|delete|changePassword|modify)/[id]',
            'model' => 'UserModel',
            'view' => 'UserView',
            'controller' => 'UserController',
            'action' => '',
        ];
        
        //start router
        $this->router = new Router($routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => true
        ));
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
        $this->assertEquals(array(), $route->getParam());
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
        $this->assertEquals(array(), $route->getParam());
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
        $this->assertEquals(array(), $route->getParam());
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
        $this->assertEquals(array('id'=>'5'), $route->getParam());
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
        $this->assertEquals(array('id'=>'5'), $route->getParam());
    }
    
    public function testRewriteModeOff()
    {
        //routes
        $routes = [];
        $routes[] = [
            'name' => '',
            'method' => 'GET',
            'url' => '/user/[id]/(disable|enable|delete|changePassword|modify)',
            'model' => 'UserModel',
            'view' => 'UserView',
            'controller' => 'UserController',
            'action' => '',
        ];
        
        //start router
        $router = new Router($routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => false
        ));
        
        //evaluate request uri
        $router->validate('/index.php?/user/5/enable', 'GET');
        
        //get route
        $route = $router->getRoute();
        
        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('UserModel', $route->getModel());
        $this->assertEquals('UserView', $route->getView());
        $this->assertEquals('UserController', $route->getController());
        $this->assertEquals('enable', $route->getAction());
        $this->assertEquals(array('id'=>'5'), $route->getParam());
    }
}
