<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

use Linna\Http\RouterCached;
use Linna\Http\Route;
use PHPUnit\Framework\TestCase;

class RouterCachedTest extends TestCase
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
        if (! class_exists('Memcached')) {
            $this->markTestSkipped('Memcached module not installed');
        }
        
        //create memcached instance
        $memcached = new Memcached();
        //connect to memcached server
        $memcached->addServer($GLOBALS['mem_host'], $GLOBALS['mem_port']);
        $memcached->flush();
        //start router
        $router = new RouterCached($this->routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => true
                ), $memcached);
        //evaluate request uri
        $router->validate('/user', 'GET');
        $router->validate('/user', 'GET');
        $router->validate('/user', 'GET');
        $router->validate('/user', 'GET');
        $router->validate('/user', 'GET');
        $router->validate('/user', 'GET');
        $router->validate('/user', 'GET');
        
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
        if (! class_exists('Memcached')) {
            $this->markTestSkipped('Memcached module not installed');
        }
        //create memcached instance
        $memcached = new Memcached();
        //connect to memcached server
        $memcached->addServer($GLOBALS['mem_host'], $GLOBALS['mem_port']);
        $memcached->flush();
        
        //start router
        $router = new RouterCached($this->routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => true
                ), $memcached);
        
        //evaluate request uri
        $router->validate('/user', 'POST');
        
        //get route
        $route = $router->getRoute();
        
        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('E404Model', $route->getModel());
        $this->assertEquals('E404View', $route->getView());
        $this->assertEquals('E404Controller', $route->getController());
        $this->assertEquals(null, $route->getAction());
        $this->assertEquals(array(), $route->getParam());
    }
    
    public function testBadMethod()
    {
        if (! class_exists('Memcached')) {
            $this->markTestSkipped('Memcached module not installed');
        }
        
        //create memcached instance
        $memcached = new Memcached();
        //connect to memcached server
        $memcached->addServer($GLOBALS['mem_host'], $GLOBALS['mem_port']);
        
        //start router
        $router = new RouterCached($this->routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => true
                ), $memcached);
        
        //evaluate request uri
        $router->validate('/badroute', 'GET');
        
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
        if (! class_exists('Memcached')) {
            $this->markTestSkipped('Memcached module not installed');
        }
        
        //create memcached instance
        $memcached = new Memcached();
        //connect to memcached server
        $memcached->addServer($GLOBALS['mem_host'], $GLOBALS['mem_port']);
        
        //start router
        $router = new RouterCached($this->routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => true
                ), $memcached);
        
        //evaluate request uri
        $router->validate('/user/5/enable', 'GET');
        
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
        if (! class_exists('Memcached')) {
            $this->markTestSkipped('Memcached module not installed');
        }
        
        //create memcached instance
        $memcached = new Memcached();
        //connect to memcached server
        $memcached->addServer($GLOBALS['mem_host'], $GLOBALS['mem_port']);
        
        //start router
        $router = new RouterCached($this->routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => true
                ), $memcached);
        
        //evaluate request uri
        $router->validate('/userOther/enable/5', 'GET');
        
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
        if (! class_exists('Memcached')) {
            $this->markTestSkipped('Memcached module not installed');
        }
        
        //create memcached instance
        $memcached = new Memcached();
        //connect to memcached server
        $memcached->addServer($GLOBALS['mem_host'], $GLOBALS['mem_port']);
        
        //start router
        $router = new RouterCached($this->routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => false
                ), $memcached);
        
        //evaluate request uri
        $router->validate('/index.php?//user/5/enable', 'GET');
        
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
