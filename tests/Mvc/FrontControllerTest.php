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

use Linna\Router\Route;
use Linna\Router\RouteCollection;
use Linna\Router\Router;
use Linna\Mvc\Model;
use Linna\Mvc\View;
use Linna\Mvc\Controller;
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
    protected static $routes;

    /**
     * @var Router The router object.
     */
    protected static $router;

    /**
     * @var Model The model object.
     */
    protected static $model;

    /**
     * @var View The view object.
     */
    protected static $view;

    /**
     * @var Controller The controller object.
     */
    protected static $controller;

    /**
     * Set up before class.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        $routes = (new RouteCollection([
            new Route([
                'name'       => 'Calculator',
                'method'     => 'POST',
                'url'        => '/calculator/(multiply|divide|add|sub)',
                'model'      =>  CalculatorModel::class,
                'view'       =>  CalculatorView::class,
                'controller' =>  CalculatorController::class,
            ]),
            new Route([
                'name'       => 'BeforeAfter',
                'method'     => 'GET',
                'url'        => '/before/after/[value]',
                'model'      => BeforeAfterModel::class,
                'view'       => BeforeAfterView::class,
                'controller' => BeforeAfterController::class,
                'action'     => 'Action'
            ]),
            new Route([
                'name'       => 'MultiParam',
                'method'     => 'GET',
                'url'        => '/multi/param/[year]/[month]/[day]',
                'model'      => MultipleModel::class,
                'view'       => MultipleView::class,
                'controller' => MultipleController::class,
                'action'     => 'SomeParam'
            ])
        ]));

        self::$router = new Router($routes->getArrayCopy(), [
            'badRoute'    => 'E404',
            'rewriteMode' => true,
        ]);

        //var_dump(self::$router);

        $model = new CalculatorModel();
        $view = new CalculatorView($model, new JsonTemplate());
        $controller = new CalculatorController($model);

        self::$model = $model;
        self::$view = $view;
        self::$controller = $controller;

        self::$routes = $routes;
    }

    /**
     * Tear down after class.
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        self::$router = null;
        self::$model = null;
        self::$view = null;
        self::$controller = null;
        self::$routes = null;
    }

    /**
     * Test new fron controller instance.
     *
     * @return void
     */
    public function testNewFrontControllerInstance(): void
    {
        $this->assertInstanceOf(FrontController::class, new FrontController(self::$model, self::$view, self::$controller, self::$routes[0]));
    }

    /**
     * Front controller arguments provider.
     *
     * @return array
     */
    public function frontControllerArgProvider(): array
    {
        $model = self::$model;
        $view = self::$view;
        $controller = self::$controller;
        $route = self::$routes[0];

        return [
            [false, $view, $controller, $route],
            [$model, false, $controller, $route],
            [$model, $view, false, $route],
            [$model, $view, $controller, false]
        ];
    }

    /**
     * Test new front controller instance with wrong arguments.
     *
     * @param Model $model
     * @param View $view
     * @param Controller $controller
     * @param Route $route
     *
     * @dataProvider frontControllerArgProvider
     * @expectedException TypeError
     *
     * @return void
     */
    public function testNewFrontControllerWithWrongArguments($model, $view, $controller, $route): void
    {
        (new FrontController($model, $view, $controller, $route));
    }

    /**
     * Calculator provider.
     *
     * @return array
     */
    public function calculatorProvider(): array
    {
        return [
            ['/calculator/multiply',[2,2,2],8],
            ['/calculator/divide',[16,2,2],4],
            ['/calculator/add',[2,2,2],6],
            ['/calculator/sub',[16,2,2],12]
        ];
    }

    /**
     * Test run front controller.
     *
     * @param string $route
     * @param array $parameter
     * @param int $result
     *
     * @dataProvider calculatorProvider
     *
     * @return void
     */
    public function testRunFrontController(string $route, array $parameter, int $result): void
    {
        $_POST['numbers'] = $parameter;

        self::$router->validate($route, 'POST');

        $frontController = new FrontController(self::$model, self::$view, self::$controller, self::$router->getRoute());
        $frontController->run();

        $this->assertEquals($result, json_decode($frontController->response())->result);
    }

    /**
     * Some param provider.
     *
     * @return array
     */
    public function someParamProvider(): array
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
     * @param string $route
     * @param string $result
     *
     * @dataProvider someParamProvider
     *
     * @return void
     */
    public function testRunFrontControllerWithSomeParam(string $route, string $result): void
    {
        self::$router->validate($route, 'GET');

        $model = new MultipleModel();
        $view = new MultipleView($model, new JsonTemplate());
        $controller = new MultipleController($model);

        $frontController = new FrontController($model, $view, $controller, self::$router->getRoute());
        $frontController->run();

        $this->assertEquals($result, json_decode($frontController->response())->result);
    }

    /**
     * Test model detach.
     *
     * @return void
     */
    public function testModelDetach(): void
    {
        self::$router->validate('/multi/param/2017/1/1', 'GET');

        $route = self::$router->getRoute();

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
    public function beforeAfterProvider(): array
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
     *
     * @return void
     */
    public function testRunFrontControllerBeforeAfter(int $input, int $result): void
    {
        self::$router->validate('/before/after/'.$input, 'GET');

        $model = new BeforeAfterModel();
        $controller = new BeforeAfterController($model);
        $view = new BeforeAfterView($model, new JsonTemplate());

        $frontController = new FrontController($model, $view, $controller, self::$router->getRoute());
        $frontController->run();

        $this->assertEquals($result, json_decode($frontController->response())->result);
    }
}
