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
    
    protected $FrontController;
    
    protected $router;
    
    public function __construct()
    {
        $this->autoloader = new Autoloader();
        $this->autoloader->register();
        $this->autoloader->addNamespaces([
           ['Linna\FOO', dirname(__DIR__).'/FOO']
        ]);
        
        $routes = array();

        $routes[] = [
            'name' => 'Foo',
            'method' => 'GET',
            'url' => '/Foo/(modifyData)',
            'model' => 'FOOModel',
            'view' => 'FOOView',
            'controller' => 'FOOController',
            'action' => null,
        ];
        
        //start router
        $this->router = new Router('/Foo/modifyData', $routes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => true
                ));
    }
    
    /**
     * @outputBuffering disabled
     */
    public function testNewFrontController()
    {
        $route = $this->router->getRoute();
        
                
        $model = new FOOModel;
        //get view linked to route
        $view = new FOOView($model, new FOOTemplate);
        //get controller linked to route
        $controller = new FOOController($model);
         
        $this->FrontController = new FrontController($this->router->getRoute(), $model, $view, $controller);
        
        $this->assertInstanceOf(FrontController::class, $this->FrontController);
        
        
        $this->FrontController->run();
        
        ob_start();
        
        $this->FrontController->response();
        
        $test = json_decode(ob_get_contents());
        
        ob_end_clean();
        
        $this->assertInstanceOf(stdClass::class, $test);
        $this->assertEquals('modified data',$test->data);
      
    }
    
    public function testRunFrontController()
    {
        //$this->assertInstanceOf(FrontController::class, $this->FrontController);
        //$this->FrontController->run();
    }
    
    public function testResponceFrontController()
    {
        //$this->assertInstanceOf(FrontController::class, $this->FrontController);
        //$this->FrontController->response();
    }
}
