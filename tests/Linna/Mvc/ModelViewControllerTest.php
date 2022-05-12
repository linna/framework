<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Mvc;

use Linna\Container\Container;
use Linna\Router\Route;
use Linna\Router\RouteCollection;
use Linna\Router\Router;
//use Linna\Mvc\Model;
//use Linna\Mvc\View;
//use Linna\Mvc\Controller;
//use Linna\Mvc\ModelViewController;
use Linna\TestHelper\Mvc\BeforeAfterController;
use Linna\TestHelper\Mvc\BeforeAfterModel;
use Linna\TestHelper\Mvc\BeforeAfterView;
use Linna\TestHelper\Mvc\CalculatorMultiController;
use Linna\TestHelper\Mvc\CalculatorMultiModel;
use Linna\TestHelper\Mvc\CalculatorMultiView;

use Linna\TestHelper\Mvc\CalculatorSingleAddModel;
use Linna\TestHelper\Mvc\CalculatorSingleAddView;
use Linna\TestHelper\Mvc\CalculatorSingleAddController;
use Linna\TestHelper\Mvc\CalculatorSingleDivideModel;
use Linna\TestHelper\Mvc\CalculatorSingleDivideView;
use Linna\TestHelper\Mvc\CalculatorSingleDivideController;
use Linna\TestHelper\Mvc\CalculatorSingleMultiplyModel;
use Linna\TestHelper\Mvc\CalculatorSingleMultiplyView;
use Linna\TestHelper\Mvc\CalculatorSingleMultiplyController;
use Linna\TestHelper\Mvc\CalculatorSingleSubModel;
use Linna\TestHelper\Mvc\CalculatorSingleSubView;
use Linna\TestHelper\Mvc\CalculatorSingleSubController;

use Linna\TestHelper\Mvc\MultipleController;
use Linna\TestHelper\Mvc\MultipleModel;
use Linna\TestHelper\Mvc\MultipleView;
use Linna\TestHelper\Mvc\JsonTemplate;
use PHPUnit\Framework\TestCase;
use TypeError;

/**
 * Model View Controller Test.
 */
class ModelViewControllerTest extends TestCase
{
    /**
     * @var RouteCollection Routes for test.
     */
    protected static RouteCollection $routes;

    /**
     * @var Router The router object.
     */
    protected static Router $router;

    /**
     * @var Model The model object.
     */
    protected static Model $model;

    /**
     * @var View The view object.
     */
    protected static View $view;

    /**
     * @var Controller The controller object.
     */
    protected static Controller $controller;

    /**
     * Set up before class.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        $routes = (new RouteCollection([
            new Route(
                name:       'Calculator Single Add',
                method:     'POST',
                path:       '/calculator/single/add',
                model:      CalculatorSingleAddModel::class,
                view:       CalculatorSingleAddView::class,
                controller: CalculatorSingleAddController::class
            ),new Route(
                name:       'Calculator Single Divide',
                method:     'POST',
                path:        '/calculator/single/divide',
                model:      CalculatorSingleDivideModel::class,
                view:       CalculatorSingleDivideView::class,
                controller: CalculatorSingleDivideController::class
            ),new Route(
                name:       'Calculator Single Multiply',
                method:     'POST',
                path:        '/calculator/single/multiply',
                model:      CalculatorSingleMultiplyModel::class,
                view:       CalculatorSingleMultiplyView::class,
                controller: CalculatorSingleMultiplyController::class,
            ),new Route(
                name:       'Calculator Single Sub',
                method:     'POST',
                path:        '/calculator/single/sub',
                model:      CalculatorSingleSubModel::class,
                view:       CalculatorSingleSubView::class,
                controller: CalculatorSingleSubController::class
            ),
            new Route(
                name:       'Calculator Multi',
                method:     'POST',
                path:        '/calculator/multi/(multiply|divide|add|sub)',
                model:      CalculatorMultiModel::class,
                view:       CalculatorMultiView::class,
                controller: CalculatorMultiController::class
            ),
            new Route(
                name:       'BeforeAfter',
                method:     'GET',
                path:        '/before/after/[value]',
                model:      BeforeAfterModel::class,
                view:       BeforeAfterView::class,
                controller: BeforeAfterController::class,
                action:     'Action'
            ),
            new Route(
                name:       'MultiParam',
                method:     'GET',
                path:        '/multi/param/[year]/[month]/[day]',
                model:      MultipleModel::class,
                view:       MultipleView::class,
                controller: MultipleController::class,
                action:     'SomeParam'
            )
        ]));

        self::$router = new Router(
            $routes,
            rewriteMode: true,
        );

        $model = new CalculatorMultiModel();
        $view = new CalculatorMultiView($model, new JsonTemplate());
        $controller = new CalculatorMultiController($model);

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
        //self::$router = null;
        //self::$model = null;
        //self::$view = null;
        //self::$controller = null;
        //self::$routes = null;
    }

    /**
     * Test new fron controller instance.
     *
     * @return void
     */
    public function testNewModelViewControllerInstance(): void
    {
        $this->assertInstanceOf(ModelViewController::class, new ModelViewController(self::$model, self::$view, self::$controller, self::$routes[0]));
    }

    /**
     * Front controller arguments provider.
     *
     * @return array
     */
    public function ModelViewControllerWrongArgProvider(): array
    {
        $model = new CalculatorMultiModel();
        $view = new CalculatorMultiView($model, new JsonTemplate());
        $controller = new CalculatorMultiController($model);
        $route = new Route(
            name:       'Calculator',
            method:     'POST',
            path:        '/calculator/(multiply|divide|add|sub)',
            model:      CalculatorMultiModel::class,
            view:       CalculatorMultiView::class,
            controller: CalculatorMultiController::class,
        );

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
     * @param Model      $model
     * @param View       $view
     * @param Controller $controller
     * @param Route      $route
     *
     * @dataProvider ModelViewControllerWrongArgProvider
     *
     * @return void
     */
    public function testNewModelViewControllerWithWrongArguments($model, $view, $controller, $route): void
    {
        $this->expectException(TypeError::class);

        (new ModelViewController($model, $view, $controller, $route));
    }

    /**
     * Calculator multi provider.
     *
     * @return array
     */
    public function calculatorMultiProvider(): array
    {
        return [
            ['/calculator/multi/multiply',[2,2,2],'Multiply: 8'],
            ['/calculator/multi/divide',[16,2,2],'Divide: 4'],
            ['/calculator/multi/add',[2,2,2],'Add: 6'],
            ['/calculator/multi/sub',[16,2,2],'Sub: 12']
        ];
    }

    /**
     * Test run front controller with multiple action model controller and view.
     *
     * @param string $route
     * @param array  $parameter
     * @param int    $result
     *
     * @dataProvider calculatorMultiProvider
     *
     * @return void
     */
    public function testRunWithMultiActionMVC(string $route, array $parameter, string $result): void
    {
        $_POST['numbers'] = $parameter;

        self::$router->validate($route, 'POST');

        $ModelViewController = new ModelViewController(self::$model, self::$view, self::$controller, self::$router->getRoute());
        $ModelViewController->run();

        $this->assertEquals($result, \json_decode($ModelViewController->response())->result);
    }

    /**
     * Calculator single provider.
     *
     * @return array
     */
    public function calculatorSingleProvider(): array
    {
        return [
            ['/calculator/single/multiply',[2,2,2],'Multiply: 8'],
            ['/calculator/single/divide',[16,2,2],'Divide: 4'],
            ['/calculator/single/add',[2,2,2],'Add: 6'],
            ['/calculator/single/sub',[16,2,2],'Sub: 12']
        ];
    }

    /**
     * Test run front controller with multiple action model controller and view.
     *
     * @param string $route
     * @param array  $parameter
     * @param int    $result
     *
     * @dataProvider calculatorSingleProvider
     *
     * @return void
     */
    public function testRunWithSingleActionMVC(string $route, array $parameter, string $result): void
    {
        $_POST['numbers'] = $parameter;

        self::$router->validate($route, 'POST');

        $routeValidated = self::$router->getRoute();

        $container = new Container();

        $model = $container->resolve($routeValidated->model);
        $view = $container->resolve($routeValidated->view);
        $controller = $container->resolve($routeValidated->controller);

        //var_dump($routeValidated->controller);

        $ModelViewController = new ModelViewController($model, $view, $controller, $routeValidated);
        $ModelViewController->run();

        $this->assertEquals($result, \json_decode($ModelViewController->response())->result);
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
    public function testRunModelViewControllerWithSomeParam(string $route, string $result): void
    {
        self::$router->validate($route, 'GET');

        $model = new MultipleModel();
        $view = new MultipleView($model, new JsonTemplate());
        $controller = new MultipleController($model);

        $ModelViewController = new ModelViewController($model, $view, $controller, self::$router->getRoute());
        $ModelViewController->run();

        $this->assertEquals($result, \json_decode($ModelViewController->response())->result);
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

        \call_user_func_array([$controller, $route->action], $route->param);

        $model->notify();

        $this->assertFalse(isset(\json_decode($view->render())->result));

        //attach
        $model->attach($view);

        \call_user_func_array([$controller, $route->action], $route->param);

        $model->notify();

        $this->assertTrue(isset(\json_decode($view->render())->result));
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
     * @param int $input
     * @param int $result
     *
     * @return void
     */
    public function testRunModelViewControllerBeforeAfter(int $input, int $result): void
    {
        self::$router->validate('/before/after/'.$input, 'GET');

        $model = new BeforeAfterModel();
        $controller = new BeforeAfterController($model);
        $view = new BeforeAfterView($model, new JsonTemplate());

        $ModelViewController = new ModelViewController($model, $view, $controller, self::$router->getRoute());
        $ModelViewController->run();

        $reponse = $ModelViewController->response();

        $this->assertEquals($result, \json_decode($reponse)->result);
        $this->assertTrue(\json_decode($reponse)->view);
    }
}
