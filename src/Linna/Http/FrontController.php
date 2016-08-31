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
     * @var Object Contain view object for render 
     */
    private $view;

    /**
     * @var Object Contain model object 
     */
    private $model;

    /**
     * @var Object Contain controller object
     */
    private $controller;

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
        
        $routeType = $route->getType();

        $routeModel = $options['modelNamespace'] . $route->getModel();
        $routeView = $options['viewNamespace'] . $route->getView();
        $routeController = $options['controllerNamespace'] . $route->getController();
        
        $routeAction = $route->getAction();
        $routeParam = $route->getParam();

        $this->model = new $routeModel();
        $this->view = new $routeView($this->model);
        $this->controller = new $routeController($this->model);

        $this->model->attach($this->view);

        $this->call($routeType, $routeAction, $routeParam);
    }

    /**
     * Call all mvc components
     * 
     * @param int $routeType
     * @param string $routeAction
     * @param array $routeParam
     */
    private function call($routeType, $routeAction, $routeParam)
    {
        //che type of route anche cal proper func
        //http://php.net/manual/en/ref.funchand.php
        //http://php.net/manual/en/function.call-user-func.php
        //http://php.net/manual/en/function.call-user-func-array.php
        switch ($routeType) {
            case 3:
                //call class, method and pass parameter
                call_user_func_array(array($this->controller, $routeAction), $routeParam);
                call_user_func(array($this->view, $routeAction));
                break;
            case 2:
                //call class, method without parameter
                call_user_func(array($this->controller, $routeAction));
                call_user_func(array($this->view, $routeAction));
                break;
            case 1:
                //call class with index, no method passed
                call_user_func(array($this->view, 'index'));
                break;
            default:
                //call default 404 controller
                call_user_func(array($this->view, 'index'));
                break;
        }
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
