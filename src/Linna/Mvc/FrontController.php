<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Mvc;

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
     * @param Model      $model
     * @param View       $view
     * @param Controller $controller
     * @param string     $action
     * @param array      $param
     */
    public function __construct(Model $model, View $view, Controller $controller, string $action, array $param)
    {
        $this->routeAction = $action;
        $this->routeParam = $param;

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
