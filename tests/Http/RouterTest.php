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

class RouterTest extends PHPUnit_Framework_TestCase
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
            'action' => null,
        ];

        $this->routes[] = [
            'name' => 'E404',
            'method' => 'GET',
            'url' => '/error',
            'model' => 'E404Model',
            'view' => 'E404View',
            'controller' => 'E404Controller',
            'action' => null,
        ];

        $this->routes[] = [
            'name' => 'User',
            'method' => 'GET',
            'url' => '/user',
            'model' => 'UserModel',
            'view' => 'UserView',
            'controller' => 'UserController',
            'action' => null,
        ];
        
        $this->routes[] = [
            'name' => null,
            'method' => 'GET',
            'url' => '/user/[id]/(disable|enable|delete|changePassword|modify)',
            'model' => 'UserModel',
            'view' => 'UserView',
            'controller' => 'UserController',
            'action' => null,
        ];
    }
    
    public function testRoute()
    {
        //start router
        $router = new Router('/user', $this->routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => true
                ));

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
        $router = new Router('/badroute', $this->routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => true
                ));

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
        $router = new Router('/user/5/enable', $this->routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => true
                ));

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
        $router = new Router('/index.php?//user/5/enable', $this->routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => false
                ));

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