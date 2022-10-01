<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Mvc;

use Linna\Router\Route;
use Linna\Router\RouteInterface;

/**
 * Model View Controller.
 *
 * <p>It behaviors as glue for the <code>Model</code>, the <code>View</code> and the <code>Controller</code> classes.<p>
 *
 * <p>The controller execution has this steps:
 *
 * * Before controller, if set.
 * * Before controller action, if set.
 * * The entry point or of the specific requested action.
 * * After controller action if set.
 * * After controller, if set.
 *<p>
 */
class ModelViewController
{
    /** @var View Contains the view object used for rendering. */
    private View $view;

    /** @var Model Contains the model object. */
    private Model $model;

    /** @var Controller Contains the controller object. */
    private Controller $controller;

    /** @var string Contains the controller and the view action name. */
    private string $routeAction = '';

    /** @var array<mixed> Paremeters passed to the controller. */
    private array $routeParam = [];

    /**
     * Class Constructor.
     *
     * @param Model          $model      The model.
     * @param View           $view       The view.
     * @param Controller     $controller The controller.
     * @param RouteInterface $route      The route which identifies the model, the view and the controller.
     */
    public function __construct(Model $model, View $view, Controller $controller, RouteInterface $route)
    {
        if ($route instanceof Route) {
            $this->routeAction = ($route->action) ?: 'entryPoint';
            $this->routeParam = $route->param;
        }

        $this->model = $model;
        $this->view = $view;
        $this->controller = $controller;
    }

    /**
     * Run the model, view, controller pattern.
     *
     * @return void
     */
    public function run(): void
    {
        //attach Observer to Subject
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
     * Executes a method call before or after the controller specific method execution.
     *
     * @param string $when Indicates if the method will be executed before or after.
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
     * Executes a method call before or after the controller execution.
     *
     * @param string $when Indicates if the method will be executed before or after.
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
     * Run controller, it executes a specific method of the controller or the standard method entryPoint.
     *
     * @return void
     */
    private function runController(): void
    {
        //get route information
        $action = $this->routeAction;
        $param = $this->routeParam;

        //if controller does not have the method named entryPoint
        //this avoid problems
        if (!\method_exists($this->controller, $action)) {
            return;
        }

        //action - call controller passing params
        if (!empty($param) && $action) {
            //PHP 8, an associative array is passed as named arguments,
            //pay attention on route declaring and to controller method arguments
            \call_user_func_array([$this->controller, $action], $param);
            return;
        }

        //action - call controller
        \call_user_func([$this->controller, $action]);
    }

    /**
     * Run the view.
     *
     * @return void
     */
    private function runView(): void
    {
        //get route information
        $action = $this->routeAction;

        if (\method_exists($this->view, $action)) {
            \call_user_func([$this->view, $action]);
        }
    }

    /**
     * Return the view data.
     *
     * @return string The result of the view rendering, template plus data.
     */
    public function response(): string
    {
        return $this->view->render();
    }
}
