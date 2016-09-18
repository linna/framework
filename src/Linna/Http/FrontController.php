<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Linna\Http;

use Linna\Http\RouteInterface;

/**
 * FrontController
 *
 */
class FrontController
{
    /**
     * @var Object $view Contain view object for render
     */
    private $view;

    /**
     * @var Object $model Contain model object
     */
    private $model;

    /**
     * @var Object $controller Contain controller object
     */
    private $controller;
    
    /**
     * @var Object $route Contain controller object
     */
    private $route;

    /**
     * Constructor
     *
     * @param RouteInterface $route Resolved route from router
     * @param object $model Model object already instantiated
     * @param object $view View object already instantiated
     * @param object $controller Controller object already instantiated
     */
    public function __construct(RouteInterface $route, $model, $view, $controller)
    {
        $this->route = $route;
        
        $this->model = $model;
        $this->view = $view;
        $this->controller = $controller;
    }
    
    /**
     * Run mvc pattern
     *
     */
    public function run()
    {
        //attach Oserver to Subjetc
        $this->model->attach($this->view);
        
        //run controller
        $this->runController();
        
        //notify model changes to view
        $this->model->notify();
        
        //run view
        $this->runView();
    }
    
    /**
     * Run controller
     *
     */
    private function runController()
    {
        $routeAction = $this->route->getAction();
        $routeParam =  $this->route->getParam();
        
        if (sizeof($routeParam) > 0 && $routeAction !== null) {
            call_user_func_array(array($this->controller, $routeAction), $routeParam);
            return;
        }
        
        if ($routeAction !== null) {
            call_user_func(array($this->controller, $routeAction));
        }
    }
    
    /**
     * Run view
     *
     */
    private function runView()
    {
        $routeAction = (($routeAction = $this->route->getAction())  !== null) ? $routeAction : 'index';
        
        call_user_func(array($this->view, $routeAction));
    }
    
    /**
     * Return view data
     *
     */
    public function response()
    {
        $this->view->render();
    }
}
