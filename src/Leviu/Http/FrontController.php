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
    private $view;
    private $controller;
       
    
    public function __construct(RouteInterface $route, $appNamespace)
    {
        $route_type = $route->getType();
        
        $route_model = '\App\Models\\'.$route->getModel();
        $route_view = '\App\Views\\'.$route->getView();
        $route_controller = '\App\Controllers\\'.$route->getController();
        $route_action = $route->getAction();
        $route_param = $route->getParam();
        
        
        $model = new $route_model();
        $this->view = new $route_view($model);
        $this->controller = new $route_controller($model);
                
        //che type of route anche cal proper func
        //http://php.net/manual/en/ref.funchand.php
        //http://php.net/manual/en/function.call-user-func.php
        //http://php.net/manual/en/function.call-user-func-array.php
        switch ($route_type) {
            case 3:
                //call class, method and pass parameter
                call_user_func_array(array($this->controller, $route_action), $route_param);
                call_user_func(array($this->view, $route_action));
                break;
            case 2:
                //call class, method without parameter
                call_user_func(array($this->controller, $route_action));
                call_user_func(array($this->view, $route_action));
                break;
            case 1:
                //call class with index, no method passed
                //call_user_func(array($this->controller, 'index'));
                call_user_func(array($this->view, 'index'));
                break;
            default:
                //call default 404 controller
                //call_user_func(array($this->controller, 'index'));
                call_user_func(array($this->view, 'index'));
                break;
        }
    }
    
    public function response()
    {
        $this->view->render();
    }
}
