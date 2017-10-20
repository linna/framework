<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Foo\Mvc\FooController;
use Linna\Foo\Mvc\FooControllerBeforeAfter;
use Linna\Foo\Mvc\FooModel;
use Linna\Foo\Mvc\FooTemplate;
use Linna\Foo\Mvc\FooView;
use Linna\Http\Route;
use Linna\Http\RouteCollection;
use Linna\Http\Router;
use Linna\Mvc\FrontController;
use PHPUnit\Framework\TestCase;

/**
 * Front Controller Test.
 */
class FrontControllerTest extends TestCase
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
     * @var Model The model object.
     */
    protected $model;
    
    /**
     * @var View The view object.
     */
    protected $view;
    
    /**
     * @var Controller The controller object.
     */
    protected $controller;
    
    /**
     * Setup.
     */
    public function setUp()
    {
        $routes = (new RouteCollection([
            new Route([
                'name'       => 'Foo',
                'method'     => 'GET',
                'url'        => '/Foo',
                'model'      => 'FOOModel',
                'view'       => 'FOOView',
                'controller' => 'FOOController',
            ]),
            new Route([
                'name'       => 'Foo',
                'method'     => 'GET',
                'url'        => '/Foo/[passedData]/(modifyDataFromParam)',
                'model'      => 'FOOModel',
                'view'       => 'FOOView',
                'controller' => 'FOOController',
            ]),
            new Route([
                'name'       => 'Foo',
                'method'     => 'GET',
                'url'        => '/Foo/(modifyDataFromSomeParam)/[year]/[month]/[day]',
                'model'      => 'FOOModel',
                'view'       => 'FOOView',
                'controller' => 'FOOController',
            ]),
            new Route([
                'name'       => 'Foo',
                'method'     => 'GET',
                'url'        => '/Foo/(modifyData)',
                'model'      => 'FOOModel',
                'view'       => 'FOOView',
                'controller' => 'FOOController',
            ]),
            new Route([
                'name'       => 'Foo',
                'method'     => 'GET',
                'url'        => '/Foo/(modifyDataTimed)',
                'model'      => 'FOOModel',
                'view'       => 'FOOView',
                'controller' => 'FOOControllerBeforeAfter',
            ])
        ]))->toArray();
        
        $this->router = new Router($routes, [
            'badRoute'    => 'E404',
            'rewriteMode' => true,
        ]);
        
        $model = new FooModel();
        $view = new FooView($model, new FooTemplate());
        $controller = new FooController($model);
        
        $this->model = $model;
        $this->view = $view;
        $this->controller = $controller;
    }

    /**
     * Test new fron controller instance.
     */
    public function testNewFrontControllerInstance()
    {
        $this->assertInstanceOf(FrontController::class, new FrontController($this->model, $this->view, $this->controller, '', []));
    }

    /**
     * Front controller arguments provider.
     *
     * @return array
     */
    public function frontControllerArgProvider() : array
    {
        $model = new FooModel();
        $view = new FooView($model, new FooTemplate());
        $controller = new FooController($model);
        
        return [
            [false, $view, $controller, 'index', []],
            [$model, false, $controller, 'index', []],
            [$model, $view, false, 'index', []],
            [$model, $view, $controller, false, []],
            [$model, $view, $controller, 'index', false]
        ];
    }
    
    /**
     * Test new front controller instance with wrong arguments.
     *
     * @dataProvider frontControllerArgProvider
     * @expectedException TypeError
     */
    public function testNewFrontControllerWithWrongArguments($model, $view, $controller, $action, $param)
    {
        (new FrontController($model, $view, $controller, $action, $param));
    }
    
    /**
     * Test run front controller
     */
    public function testRunFrontController()
    {
        $this->router->validate('/Foo/modifyData', 'GET');

        $route = $this->router->getRoute()->toArray();

        $frontController = new FrontController($this->model, $this->view, $this->controller, $route['action'], $route['param']);

        $frontController->run();

        $test = json_decode($frontController->response());

        $this->assertInstanceOf(stdClass::class, $test);
        $this->assertEquals(1234, $test->data);
    }

    /**
     * Test run front controller with param
     */
    public function testRunFrontControllerWithParam()
    {
        $this->router->validate('/Foo/500/modifyDataFromParam', 'GET');

        $route = $this->router->getRoute()->toArray();

        $frontController = new FrontController($this->model, $this->view, $this->controller, $route['action'], $route['param']);

        $frontController->run();

        $test = json_decode($frontController->response());

        $this->assertInstanceOf(stdClass::class, $test);
        $this->assertEquals(500, $test->data);
    }
    
    /**
     * Test run front controller with param
     */
    public function testRunFrontControllerWithSomeParam()
    {
        $this->router->validate('/Foo/modifyDataFromSomeParam/2017/10/20', 'GET');

        $route = $this->router->getRoute()->toArray();

        $frontController = new FrontController($this->model, $this->view, $this->controller, $route['action'], $route['param']);

        $frontController->run();

        $test = json_decode($frontController->response());

        $this->assertInstanceOf(stdClass::class, $test);
        $this->assertEquals('2017-10-20 01:02:03', $test->data);
    }

    /**
     * Test model detach.
     */
    public function testModelDetach()
    {
        $this->router->validate('/Foo/data500/modifyDataFromParam', 'GET');

        $route = $this->router->getRoute();

        $action = (($action = $route->getAction()) !== null) ? $action : 'index';
        
        $this->model->attach($this->view);
        $this->model->detach($this->view);

        call_user_func_array([$this->controller, $action], $route->getParam());

        $this->model->notify();

        call_user_func([$this->view, $action]);

        $test = json_decode($this->view->render());

        $this->assertInstanceOf(stdClass::class, $test);
        $this->assertEquals(false, isset($test->data));
    }

    /**
     * Test run front controller with action.
     */
    public function testRunFrontControllerWithAction()
    {
        $this->router->validate('/Foo/modifyDataTimed', 'GET');

        $route = $this->router->getRoute()->toArray();

        $controller = new FooControllerBeforeAfter($this->model);

        $frontController = new FrontController($this->model, $this->view, $controller, $route['action'], $route['param']);

        $frontController->run();

        $test = json_decode($frontController->response());

        $this->assertInstanceOf(stdClass::class, $test);
        $this->assertEquals(123, (int) $test->data);
    }
    
    /**
      * Test run front controller without action.
     */
    public function testRunFrontControllerWithOutAction()
    {
        $this->router->validate('/Foo', 'GET');

        $route = $this->router->getRoute()->toArray();

        $frontController = new FrontController($this->model, $this->view, $this->controller, $route['action'], $route['param']);

        $frontController->run();

        $test = json_decode($frontController->response());

        $this->assertInstanceOf(stdClass::class, $test);
    }
}
