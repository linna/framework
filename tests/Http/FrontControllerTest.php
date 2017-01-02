<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

use Linna\Autoloader;
use Linna\Http\FrontController;
use Linna\Http\Router;
use Linna\FOO\FOOModel;
use Linna\FOO\FOOView;
use Linna\FOO\FOOController;
use Linna\FOO\FOOTemplate;
use PHPUnit\Framework\TestCase;

class FrontControllerTest extends TestCase
{
    protected $routes;
    
    public function __construct()
    {
        $autoloader = new Autoloader();
        $autoloader->register();
        $autoloader->addNamespaces([
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
            'action' => '',
        ];
        
        $this->routes[] = [
            'name' => 'Foo',
            'method' => 'GET',
            'url' => '/Foo/[passedData]/(modifyDataFromParam)',
            'model' => 'FOOModel',
            'view' => 'FOOView',
            'controller' => 'FOOController',
            'action' => '',
        ];
    }
    
    /**
     * @outputBuffering disabled
     */
    public function testNewFrontController()
    {
        //start router
        $router = new Router($this->routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => true
                ));
        
        //evaluate request uri
        $router->validate('/Foo/modifyData', 'GET');
        
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
        $router = new Router($this->routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => true
                ));
        //evaluate request uri
        $router->validate('/Foo/modifyData', 'GET');
        
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
        $router = new Router($this->routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => true
                ));
        //evaluate request uri
        $router->validate('/Foo/data500/modifyDataFromParam', 'GET');
        
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
        $router = new Router($this->routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => true
                ));
        //evaluate request uri
        $router->validate('/Foo/data500/modifyDataFromParam', 'GET');
        
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
