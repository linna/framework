<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

use Linna\Autoloader;
use Linna\Http\FrontController;
use Linna\Http\Router;
//use Linna\Http\Route;
//use Linna\Mvc\Model;
//use Linna\Mvc\View;
//use Linna\Mvc\Controller;

use Linna\FOO\FOOModel;
use Linna\FOO\FOOView;
use Linna\FOO\FOOController;
use Linna\FOO\FOOTemplate;

class FrontControllerTest extends PHPUnit_Framework_TestCase
{
    protected $autoloader;
    
    protected $routes;
    
    public function __construct()
    {
        $this->autoloader = new Autoloader();
        $this->autoloader->register();
        $this->autoloader->addNamespaces([
           ['Linna\FOO', dirname(__DIR__).'/FOO']
        ]);
        
        $this->routes = array();

        $this->routes[] = [
            'name' => 'Foo',
            'method' => 'GET',
            'url' => '/Foo/(modifyData)',
            'model' => 'FOOModel',
            'view' => 'FOOView',
            'controller' => 'FOOController',
            'action' => null,
        ];
        
        $this->routes[] = [
            'name' => 'Foo',
            'method' => 'GET',
            'url' => '/Foo/[passedData]/(modifyDataFromParam)',
            'model' => 'FOOModel',
            'view' => 'FOOView',
            'controller' => 'FOOController',
            'action' => null,
        ];
    }
    
    /**
     * @outputBuffering disabled
     */
    public function testNewFrontController()
    {
        //start router
        $router = new Router('/Foo/modifyData', $this->routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => true
                ));
        
        $model = new FOOModel;
        //get view linked to route
        $view = new FOOView($model, new FOOTemplate);
        //get controller linked to route
        $controller = new FOOController($model);
         
        $FrontController = new FrontController($router->getRoute(), $model, $view, $controller);
        
        $this->assertInstanceOf(FrontController::class, $FrontController);
    }
    
    /**
     * @depends testNewFrontController
     */
    public function testRunFrontController()
    {
        //start router
        $router = new Router('/Foo/modifyData', $this->routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => true
                ));
        
        $model = new FOOModel;
        //get view linked to route
        $view = new FOOView($model, new FOOTemplate);
        //get controller linked to route
        $controller = new FOOController($model);
         
        $FrontController = new FrontController($router->getRoute(), $model, $view, $controller);
        
        $FrontController->run();
        
        ob_start();
        
        $FrontController->response();
        
        $test = json_decode(ob_get_contents());
        
        ob_end_clean();
        
        $this->assertInstanceOf(stdClass::class, $test);
        $this->assertEquals('modified data', $test->data);
    }
    
    /**
     * @depends testNewFrontController
     */
    public function testRunFrontControllerParam()
    {
        //start router
        $router = new Router('/Foo/data500/modifyDataFromParam', $this->routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => true
                ));
        
        $model = new FOOModel;
        //get view linked to route
        $view = new FOOView($model, new FOOTemplate);
        //get controller linked to route
        $controller = new FOOController($model);
         
        $FrontController = new FrontController($router->getRoute(), $model, $view, $controller);
        
        $FrontController->run();
        
        ob_start();
        
        $FrontController->response();
        
        $test = json_decode(ob_get_contents());
        
        ob_end_clean();
        
        $this->assertInstanceOf(stdClass::class, $test);
        $this->assertEquals('data500', $test->data);
    }
    
    public function testModelDetach()
    {
        
        $router = new Router('/Foo/data500/modifyDataFromParam', $this->routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => true
                ));
        
        $route = $router->getRoute();
        
        $routeAction = $route->getAction();
        $routeParam =  $route->getParam();
        
        $model = new FOOModel;
        
        $view = new FOOView($model, new FOOTemplate);
        
        $controller = new FOOController($model);
        
        $model->attach($view);
        $model->detach($view);
        
        call_user_func_array(array($controller, $routeAction), $routeParam);
        
        $model->notify();
        
        $routeAction = ($routeAction !== null) ? $routeAction : 'index';
        
        call_user_func(array($view, $routeAction));
        
        ob_start();
        
        $view->render();
        
        $test = json_decode(ob_get_contents());
        
        ob_end_clean();
                
        $this->assertInstanceOf(stdClass::class, $test);
        
        $this->assertEquals(false, isset($test->data));
    }
}
