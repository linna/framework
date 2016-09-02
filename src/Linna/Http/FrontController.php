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
    use \Linna\classOptionsTrait;

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
     * Utilized with classOptionsTrait
     *
     * @var array Config options for class
     */
    protected $options = array(
        'modelNamespace' => '',
        'viewNamespace' => '',
        'controllerNamespace' => '',
    );

    /**
     * Constructor
     *
     * @param RouteInterface $route
     * @param array $options
     */
    public function __construct(RouteInterface $route, $options)
    {
        $this->options = $this->overrideOptions($this->options, $options);
        
        $routeModel = $options['modelNamespace'].$route->getModel();
        $routeView = $options['viewNamespace'].$route->getView();
        $routeController = $options['controllerNamespace'].$route->getController();
        
        $this->route = $route;
        $this->model = new $routeModel();
        $this->view = new $routeView($this->model);
        $this->controller = new $routeController($this->model);
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
    
    private function runView()
    {
        $routeAction = $this->route->getAction();
        $routeAction = ($routeAction !== null) ? $routeAction : 'index';
        
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
