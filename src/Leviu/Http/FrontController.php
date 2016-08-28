<?php

/**
 * Leviu
 *
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Leviu\Http;

use Leviu\Http\RouteInterface;

/**
 * Description of FrontController
 *
 * @author Sebastian
 */
class FrontController
{

    use \Leviu\classOptionsTrait;

    /**
     *
     * @var Object Contain view object for render 
     */
    private $view;

    /**
     *
     * @var Object Contain model object 
     */
    private $model;

    /**
     *
     * @var Object Contain controller object
     */
    private $controller;

    /**
     * Utilized with classOptionsTrait
     * @var array Config options for class
     */
    protected $options = array(
        'modelNamespace' => '',
        'viewNamespace' => '',
        'controllerNamespace' => '',
    );

    /**
     * constructor
     * 
     * @param RouteInterface $route
     * @param type $appNamespace
     */
    public function __construct(RouteInterface $route, $options)
    {
        $this->overrideOptions($options);
        
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

    public function response()
    {
        $this->view->render();
    }

}
