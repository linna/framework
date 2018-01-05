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

/**
 * FrontController.
 */
class FrontController
{
    /**
     * @var View Contain view object for render
     */
    private $view;

    /**
     * @var Model Contain model object
     */
    private $model;

    /**
     * @var Controller Contain controller object
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
     */
    private function beforeAfterControllerAction(string $when)
    {
        $method = $when.ucfirst($this->routeAction);

        if (method_exists($this->controller, $method) && $method !== $when) {
            call_user_func([$this->controller, $method]);
        }
    }

    /**
     * Run action before or after controller execution.
     *
     * @param string $when
     */
    private function beforeAfterController(string $when)
    {
        if (method_exists($this->controller, $when)) {
            call_user_func([$this->controller, $when]);
        }
    }

    
    /**
     * Run controller.
     */
    private function runController()
    {
        //get route information
        $action = $this->routeAction;
        $param = $this->routeParam;

        //action - call controller passing params
        if ($param) {
            call_user_func_array([$this->controller, $action], $param);
            return;
        }

        //action - call controller
        if ($action) {
            call_user_func([$this->controller, $action]);
        }
    }

    /**
     * Run view.
     */
    private function runView()
    {
        $action = ($this->routeAction) ? $this->routeAction : 'index';
        
        call_user_func([$this->view, $action]);
    }

    /**
     * Return view data.
     */
    public function response() : string
    {
        return $this->view->render();
    }
}
