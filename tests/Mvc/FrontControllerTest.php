<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\FOO\FOOController;
use Linna\FOO\FOOControllerBeforeAfter;
use Linna\FOO\FOOModel;
use Linna\FOO\FOOTemplate;
use Linna\FOO\FOOView;
use Linna\Http\Router;
use Linna\Mvc\FrontController;
use PHPUnit\Framework\TestCase;

class FrontControllerTest extends TestCase
{
    protected $routes;

    protected $router;

    public function setUp()
    {
        $routes = [];

        $routes[] = [
            'name'       => 'Foo',
            'method'     => 'GET',
            'url'        => '/Foo',
            'model'      => 'FOOModel',
            'view'       => 'FOOView',
            'controller' => 'FOOController',
            'action'     => '',
        ];

        $routes[] = [
            'name'       => 'Foo',
            'method'     => 'GET',
            'url'        => '/Foo/[passedData]/(modifyDataFromParam)',
            'model'      => 'FOOModel',
            'view'       => 'FOOView',
            'controller' => 'FOOController',
            'action'     => '',
        ];

        $routes[] = [
            'name'       => 'Foo',
            'method'     => 'GET',
            'url'        => '/Foo/(modifyData)',
            'model'      => 'FOOModel',
            'view'       => 'FOOView',
            'controller' => 'FOOController',
            'action'     => '',
        ];

        $routes[] = [
            'name'       => 'Foo',
            'method'     => 'GET',
            'url'        => '/Foo/(modifyDataTimed)',
            'model'      => 'FOOModel',
            'view'       => 'FOOView',
            'controller' => 'FOOControllerBeforeAfter',
            'action'     => '',
        ];

        //start router
        $this->router = new Router($routes, [
            'basePath'    => '/',
            'badRoute'    => 'E404',
            'rewriteMode' => true,
        ]);
    }

    /**
     * @outputBuffering disabled
     */
    public function testNewFrontController()
    {
        //evaluate request uri
        //$this->router->validate('/Foo', 'GET');

        $model = new FOOModel();
        //get view linked to route
        $view = new FOOView($model, new FOOTemplate());
        //get controller linked to route
        $controller = new FOOController($model);

        $FrontController = new FrontController($model, $view, $controller, '', []);

        $this->assertInstanceOf(FrontController::class, $FrontController);
    }

    /**
     * @depends testNewFrontController
     */
    public function testRunFrontController()
    {
        //evaluate request uri
        $this->router->validate('/Foo/modifyData', 'GET');

        $route = $this->router->getRoute()->getArray();

        $model = new FOOModel();
        //get view linked to route
        $view = new FOOView($model, new FOOTemplate());
        //get controller linked to route
        $controller = new FOOController($model);

        $FrontController = new FrontController($model, $view, $controller, $route['action'], $route['param']);

        $FrontController->run();

        ob_start();

        $FrontController->response();

        $test = json_decode(ob_get_contents());

        ob_end_clean();

        $this->assertInstanceOf(stdClass::class, $test);
        $this->assertEquals(1234, $test->data);
    }

    /**
     * @depends testNewFrontController
     */
    public function testRunFrontControllerParam()
    {
        //evaluate request uri
        $this->router->validate('/Foo/500/modifyDataFromParam', 'GET');

        $route = $this->router->getRoute()->getArray();

        $model = new FOOModel();
        //get view linked to route
        $view = new FOOView($model, new FOOTemplate());
        //get controller linked to route
        $controller = new FOOController($model);

        $FrontController = new FrontController($model, $view, $controller, $route['action'], $route['param']);

        $FrontController->run();

        ob_start();

        $FrontController->response();

        $test = json_decode(ob_get_contents());

        ob_end_clean();

        $this->assertInstanceOf(stdClass::class, $test);
        $this->assertEquals(500, $test->data);
    }

    public function testModelDetach()
    {
        //evaluate request uri
        $this->router->validate('/Foo/data500/modifyDataFromParam', 'GET');

        $route = $this->router->getRoute();

        $routeAction = $route->getAction();
        $routeParam = $route->getParam();

        $model = new FOOModel();

        $view = new FOOView($model, new FOOTemplate());

        $controller = new FOOController($model);

        $model->attach($view);
        $model->detach($view);

        call_user_func_array([$controller, $routeAction], $routeParam);

        $model->notify();

        $routeAction = ($routeAction !== null) ? $routeAction : 'index';

        call_user_func([$view, $routeAction]);

        ob_start();

        $view->render();

        $test = json_decode(ob_get_contents());

        ob_end_clean();

        $this->assertInstanceOf(stdClass::class, $test);
        $this->assertEquals(false, isset($test->data));
    }

    /**
     * @depends testNewFrontController
     */
    public function testRunFrontControllerWithActions()
    {
        //evaluate request uri
        $this->router->validate('/Foo/modifyDataTimed', 'GET');

        $route = $this->router->getRoute()->getArray();

        $model = new FOOModel();
        //get view linked to route
        $view = new FOOView($model, new FOOTemplate());
        //get controller linked to route
        $controller = new FOOControllerBeforeAfter($model);

        $FrontController = new FrontController($model, $view, $controller, $route['action'], $route['param']);

        $FrontController->run();

        ob_start();

        $FrontController->response();

        $test = json_decode(ob_get_contents());

        var_dump($test);

        ob_end_clean();

        $this->assertInstanceOf(stdClass::class, $test);
        $this->assertEquals(123, (int) $test->data);
    }
}
