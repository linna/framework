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

use Linna\Router\Route;
use Linna\Router\RouteInterface;

/**
 * FrontController.
 */
class FrontController
{
    /**
     * @var View Contain view object for render
     */
    private View $view;

    /**
     * @var Model Contain model object
     */
    private Model $model;

    /**
     * @var Controller Contain controller object
     */
    private Controller $controller;

    /**
     * @var string Contain Controller and View action name
     */
    private string $routeAction = '';

    /**
     * @var array Paremeter passed to Controller
     */
    private array $routeParam = [];

    /**
     * Constructor.
     *
     * @param Model          $model
     * @param View           $view
     * @param Controller     $controller
     * @param RouteInterface $route
     */
    public function __construct(Model $model, View $view, Controller $controller, RouteInterface $route)
    {
        if ($route instanceof Route) {
            $this->routeAction = $route->action;
            $this->routeParam = $route->param;
        }

        $this->model = $model;
        $this->view = $view;
        $this->controller = $controller;
    }

    /**
     * Run mvc pattern.
     *
     * @return void
     */
    public function run(): void
    {
        //attach Oserver to Subjetc
        $this->model->attach($this->view);

        //run action before controller
        $this->beforeAfterController('before');
        $this->beforeAfterControllerAction('before');

        //run controller
        $this->runController();

        //run action after controller
        $this->beforeAfterControllerAction('after');
        $this->beforeAfterController('after');

        //notify model changes to view
        $this->model->notify();

        //run view
        $this->runView();
    }

    /**
     * Run action before or after controller action execution.
     *
     * @param string $when
     *
     * @return void
     */
    private function beforeAfterControllerAction(string $when): void
    {
        $method = $when.\ucfirst($this->routeAction);

        if (\method_exists($this->controller, $method) && $method !== $when) {
            \call_user_func([$this->controller, $method]);
        }
    }

    /**
     * Run action before or after controller execution.
     *
     * @param string $when
     *
     * @return void
     */
    private function beforeAfterController(string $when): void
    {
        if (\method_exists($this->controller, $when)) {
            \call_user_func([$this->controller, $when]);
        }
    }

    /**
     * Run controller.
     *
     * @return void
     */
    private function runController(): void
    {
        //get route information
        $action = $this->routeAction;
        $param = $this->routeParam;

        //action - call controller passing params
        if (!empty($param) && $action) {
            \call_user_func_array([$this->controller, $action], $param);
            return;
        }

        //action - call controller
        if ($action) {
            \call_user_func([$this->controller, $action]);
        }
    }

    /**
     * Run view.
     *
     * @return void
     */
    private function runView(): void
    {
        $action = ($this->routeAction) ?: 'index';

        if (\method_exists($this->view, $action)) {
            \call_user_func([$this->view, $action]);
        }
    }

    /**
     * Return view data.
     *
     * @return string
     */
    public function response(): string
    {
        return $this->view->render();
    }
}
