<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

use Linna\Http\Router;
use Linna\Http\Route;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    protected $routes;
    
    public function __construct()
    {
        $this->routes = array();

        $this->routes[] = [
            'name' => 'Home',
            'method' => 'GET',
            'url' => '/',
            'model' => 'HomeModel',
            'view' => 'HomeView',
            'controller' => 'HomeController',
            'action' => '',
        ];

        $this->routes[] = [
            'name' => 'E404',
            'method' => 'GET',
            'url' => '/error',
            'model' => 'E404Model',
            'view' => 'E404View',
            'controller' => 'E404Controller',
            'action' => '',
        ];

        $this->routes[] = [
            'name' => 'User',
            'method' => 'GET',
            'url' => '/user',
            'model' => 'UserModel',
            'view' => 'UserView',
            'controller' => 'UserController',
            'action' => '',
        ];
        
        $this->routes[] = [
            'name' => '',
            'method' => 'GET',
            'url' => '/user/[id]/(disable|enable|delete|changePassword|modify)',
            'model' => 'UserModel',
            'view' => 'UserView',
            'controller' => 'UserController',
            'action' => '',
        ];
        
        $this->routes[] = [
            'name' => '',
            'method' => 'GET',
            'url' => '/userOther/(disable|enable|delete|changePassword|modify)/[id]',
            'model' => 'UserModel',
            'view' => 'UserView',
            'controller' => 'UserController',
            'action' => '',
        ];
    }
    
    public function testRoute()
    {
        //start router
        $router = new Router($this->routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => true
                ));
        //evaluate request uri
        $router->validate('/user');
        
        //get route
        $route = $router->getRoute();
        
        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('UserModel', $route->getModel());
        $this->assertEquals('UserView', $route->getView());
        $this->assertEquals('UserController', $route->getController());
        $this->assertEquals(null, $route->getAction());
        $this->assertEquals(array(), $route->getParam());
    }
    
    public function testBadRoute()
    {
        //start router
        $router = new Router($this->routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => true
                ));
        
        //evaluate request uri
        $router->validate('/badroute');
        
        //get route
        $route = $router->getRoute();
        
        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('E404Model', $route->getModel());
        $this->assertEquals('E404View', $route->getView());
        $this->assertEquals('E404Controller', $route->getController());
        $this->assertEquals(null, $route->getAction());
        $this->assertEquals(array(), $route->getParam());
    }
    
    public function testParamRoute()
    {
        //start router
        $router = new Router($this->routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => true
                ));
        
        //evaluate request uri
        $router->validate('/user/5/enable');
        
        //get route
        $route = $router->getRoute();
        
        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('UserModel', $route->getModel());
        $this->assertEquals('UserView', $route->getView());
        $this->assertEquals('UserController', $route->getController());
        $this->assertEquals('enable', $route->getAction());
        $this->assertEquals(array('id'=>'5'), $route->getParam());
    }
    
    public function testInverseParamRoute()
    {
        //start router
        $router = new Router($this->routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => true
                ));
        
        //evaluate request uri
        $router->validate('/userOther/enable/5');
        
        //get route
        $route = $router->getRoute();
        
        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('UserModel', $route->getModel());
        $this->assertEquals('UserView', $route->getView());
        $this->assertEquals('UserController', $route->getController());
        $this->assertEquals('enable', $route->getAction());
        $this->assertEquals(array('id'=>'5'), $route->getParam());
    }
    
    public function testRewriteModeOff()
    {
        //start router
        $router = new Router($this->routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => false
                ));
        
        //evaluate request uri
        $router->validate('/index.php?//user/5/enable');
        
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
