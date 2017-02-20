<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Http;

use Linna\Mvc\Controller;
use Linna\Mvc\Model;
use Linna\Mvc\View;

/**
 * FrontController.
 */
class FrontController
{
    /**
     * @var object Contain view object for render
     */
    private $view;

    /**
     * @var object Contain model object
     */
    private $model;

    /**
     * @var object Contain controller object
     */
    private $controller;

    /**
     * @var object Contain controller object
     */
    private $route;

    /**
     * @var string Contain Controller and View action name
     */
    private $routeAction;

    /**
     * @var array Paremeter passed to Controller
     */
    private $routeParam;

    /**
     * Constructor.
     *
     * @param RouteInterface $route      Resolved route from router
     * @param object         $model      Model object already instantiated
     * @param object         $view       View object already instantiated
     * @param object         $controller Controller object already instantiated
     */
    public function __construct(RouteInterface $route, Model $model, View $view, Controller $controller)
    {
        $this->route = $route;

        $this->routeAction = $route->getAction();
        $this->routeParam = $route->getParam();

        $this->model = $model;
        $this->view = $view;
        $this->controller = $controller;
    }

    /**
     * Run mvc pattern.
     */
    public function run()
    {
        //attach Oserver to Subjetc
        $this->model->attach($this->view);

        //run action before controller
        $this->beforeAfterControllerAction('before');

        //run controller
        $this->runController();

        //run action after controller
        $this->beforeAfterControllerAction('after');

        //notify model changes to view
        $this->model->notify();

        //run view
        $this->runView();
    }

    /**
     * Run action before or after controller execution.
     * 
     * @param string $when
     */
    private function beforeAfterControllerAction(string $when)
    {
        //check for before action method
        if (method_exists($this->controller, $when)) {
            $this->controller->before();
        }

        $actionMethod = $when.ucfirst($this->routeAction);

        if (method_exists($this->controller, $actionMethod) && $actionMethod !== $when) {
            call_user_func([$this->controller, $actionMethod]);
        }
    }

    /**
     * Run controller.
     */
    private function runController()
    {
        //get route information
        $routeAction = $this->routeAction;
        $routeParam = $this->routeParam;

        //action - call controller passing params
        if (count($routeParam) > 0 && $routeAction !== '') {
            call_user_func_array([$this->controller, $routeAction], $routeParam);

            return;
        }

        //action - call controller
        if ($routeAction !== '') {
            call_user_func([$this->controller, $routeAction]);
        }
    }

    /**
     * Run view.
     */
    private function runView()
    {
        $routeAction = (($routeAction = $this->routeAction) !== '') ? $routeAction : 'index';

        call_user_func([$this->view, $routeAction]);
    }

    /**
     * Return view data.
     */
    public function response()
    {
        $this->view->render();
    }
}
