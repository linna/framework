<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Http\Route;
use Linna\Http\RouteCollection;
use Linna\Http\Router;
use Linna\Mvc\FrontController;
use Linna\TestHelper\Mvc\BeforeAfterController;
use Linna\TestHelper\Mvc\BeforeAfterModel;
use Linna\TestHelper\Mvc\BeforeAfterView;
use Linna\TestHelper\Mvc\CalculatorController;
use Linna\TestHelper\Mvc\CalculatorModel;
use Linna\TestHelper\Mvc\CalculatorView;
use Linna\TestHelper\Mvc\MultipleController;
use Linna\TestHelper\Mvc\MultipleModel;
use Linna\TestHelper\Mvc\MultipleView;
use Linna\TestHelper\Mvc\JsonTemplate;

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
                'name'       => 'Calculator',
                'method'     => 'POST',
                'url'        => '/calculator/(multiply|divide|add|sub)',
                'model'      => 'CalculatorModel',
                'view'       => 'CalculatorView',
                'controller' => 'CalculatorController',
            ]),
            new Route([
                'name'       => 'BeforeAfter',
                'method'     => 'GET',
                'url'        => '/before/after/[value]',
                'model'      => 'BeforeAfterModel',
                'view'       => 'BeforeAfterView',
                'controller' => 'BeforeAfterController',
                'action'     => 'Action'
            ]),
            new Route([
                'name'       => 'MultiParam',
                'method'     => 'GET',
                'url'        => '/multi/param/[year]/[month]/[day]',
                'model'      => 'MultiModel',
                'view'       => 'MultiView',
                'controller' => 'MultiController',
                'action'     => 'SomeParam'
            ])
        ]))->toArray();
        
        $this->router = new Router($routes, [
            'badRoute'    => 'E404',
            'rewriteMode' => true,
        ]);
        
        $model = new CalculatorModel();
        $view = new CalculatorView($model, new JsonTemplate());
        $controller = new CalculatorController($model);
        
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
        $model = $this->model;
        $view = $this->view;
        $controller = $this->controller;
        
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
     * Calculator provider.
     *
     * @return array
     */
    public function calculatorProvider() : array
    {
        return [
            ['/calculator/multiply',[2,2,2],8],
            ['/calculator/divide',[16,2,2],4],
            ['/calculator/add',[2,2,2],6],
            ['/calculator/sub',[16,2,2],12]
        ];
    }

    /**
     * Test run front controller
     *
     * @dataProvider calculatorProvider
     */
    public function testRunFrontController(string $route, array $parameter, int $result)
    {
        $_POST['numbers'] = $parameter;
        
        $this->router->validate($route, 'POST');

        $routeArray = $this->router->getRoute()->toArray();

        $frontController = new FrontController($this->model, $this->view, $this->controller, $routeArray['action'], $routeArray['param']);

        $frontController->run();

        //var_dump(json_decode($frontController->response())->result);
        
        $this->assertEquals($result, json_decode($frontController->response())->result);
        //$this->assertTrue(true);
    }

    /**
     * Some param provider.
     *
     * @return array
     */
    public function someParamProvider() : array
    {
        return [
            ['/multi/param/2017/1/1','2017-01-01 12:00:00'],
            ['/multi/param/2018/2/2','2018-02-02 12:00:00'],
            ['/multi/param/2019/3/3','2019-03-03 12:00:00'],
            ['/multi/param/2020/4/4','2020-04-04 12:00:00'],
            ['/multi/param/2021/5/5','2021-05-05 12:00:00']
        ];
    }
    
    /**
     * Test run front controller with param.
     *
     * @dataProvider someParamProvider
     */
    public function testRunFrontControllerWithSomeParam(string $route, string $result)
    {
        $this->router->validate($route, 'GET');

        $route = $this->router->getRoute()->toArray();

        $model = new MultipleModel();
        $view = new MultipleView($model, new JsonTemplate());
        $controller = new MultipleController($model);
        
        $frontController = new FrontController($model, $view, $controller, $route['action'], $route['param']);

        $frontController->run();

        $this->assertEquals($result, json_decode($frontController->response())->result);
    }

    /**
     * Test model detach.
     */
    public function testModelDetach()
    {
        $this->router->validate('/multi/param/2017/1/1', 'GET');

        $route = $this->router->getRoute();

        $model = new MultipleModel();
        $view = new MultipleView($model, new JsonTemplate());
        $controller = new MultipleController($model);
        
        //attach and detach
        $model->attach($view);
        $model->detach($view);

        call_user_func_array([$controller, $route->getAction()], $route->getParam());

        $model->notify();

        $this->assertFalse(isset(json_decode($view->render())->result));
        
        //attach
        $model->attach($view);

        call_user_func_array([$controller, $route->getAction()], $route->getParam());

        $model->notify();
        
        $this->assertTrue(isset(json_decode($view->render())->result));
    }

    /**
     * Calculator provider.
     *
     * @return array
     */
    public function beforeAfterProvider() : array
    {
        return [
            [10,15],
            [20,25],
            [30,35],
            [40,45],
            [50,55],
        ];
    }
    
    /**
     * Test run front controller before after.
     *
     * @dataProvider beforeAfterProvider
     */
    public function testRunFrontControllerBeforeAfter(int $input, int $result)
    {
        $this->router->validate('/before/after/'.$input, 'GET');

        $route = $this->router->getRoute()->toArray();

        $model = new BeforeAfterModel();
        $controller = new BeforeAfterController($model);
        $view = new BeforeAfterView($model, new JsonTemplate());
        
        $frontController = new FrontController($model, $view, $controller, $route['action'], $route['param']);

        $frontController->run();

        $this->assertEquals($result, json_decode($frontController->response())->result);
    }
}
