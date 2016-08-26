<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
    /**
     *
     * @var Object Contain view object for render 
     */
    private $view;
    
    /**
     * constructor
     * 
     * @param RouteInterface $route
     * @param type $appNamespace
     */
    public function __construct(RouteInterface $route, $appNamespace)
    {
        $routeType = $route->getType();
        
        $routeModel = $appNamespace->model.$route->getModel();
        $routeView = $appNamespace->view.$route->getView();
        $routeController = $appNamespace->controller.$route->getController();
        $routeAction = $route->getAction();
        $routeParam = $route->getParam();
        
        
        $model = new $routeModel();
        $view = new $routeView($model);
        $controller = new $routeController($model);
        
        $model->attach($view);
        
        
        //che type of route anche cal proper func
        //http://php.net/manual/en/ref.funchand.php
        //http://php.net/manual/en/function.call-user-func.php
        //http://php.net/manual/en/function.call-user-func-array.php
        switch ($routeType) {
            case 3:
                //call class, method and pass parameter
                call_user_func_array(array($controller, $routeAction), $routeParam);
                call_user_func(array($view, $routeAction));
                break;
            case 2:
                //call class, method without parameter
                call_user_func(array($controller, $routeAction));
                call_user_func(array($view, $routeAction));
                break;
            case 1:
                //call class with index, no method passed
                call_user_func(array($view, 'index'));
                break;
            default:
                //call default 404 controller
                call_user_func(array($view, 'index'));
                break;
        }
        
        $this->view = $view;
    }
    
    public function response()
    {
        $this->view->render();
    }
}
