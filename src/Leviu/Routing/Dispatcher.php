<?php

/**
 * Leviu
 *
 * This work would be a little PHP framework, a learn exercice. 
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2015, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 * @version 0.1.0
 */

namespace Leviu\Routing;

/**
 * Dispatcher 
 * - Class for dispatch routes, accept a route object and call proper
 * method after check route type
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 */
class Dispatcher
{
    public static $appNamespace = '';
    public static $controller404 = '';
    
    /**
     * @var object Valid Route instance
     */
    private $route = null;

    /**
     * @var object The controller 
     */
    private $url_controller = null;

    /**
     * @var string The method (of the above controller), often also named "action"
     */
    private $url_action = null;

    /**
     * @var array URL parameters 
     */
    private $url_params = array();

    /**
     * Dispatcher constructor
     * 
     * @param \App_mk0\RouteInterface $route A valid istance of RouteInterface
     */
    public function __construct(RouteInterface $route)
    {
        //get passed route
        $this->route = $route;

        //get app namespace for find controller and methods
        $appNamespace = self::$appNamespace;
        
        //get controller
        $controller = $appNamespace.$route->getController();
        
        //get 404 controller
        $controller404 = $appNamespace.self::$controller404; 
        
        //create istance of controller if valid else create 404 controller :)
        $this->url_controller = (class_exists($controller)) ? new $controller() : new $controller404();

        //get method
        /**
         * @todo Check if method is not valid and go to 404 controller
         */
        $this->url_action = $route->getAction();

        //get param
        $this->url_params = $route->getParam();
    }
    
    /**
     * dispatch
     * 
     * Dispatch passed route;
     */
    public function dispatch()
    {
        $type = $this->route->getType();

        //che type of route anche cal proper func
        //http://php.net/manual/en/ref.funchand.php
        //http://php.net/manual/en/function.call-user-func.php
        //http://php.net/manual/en/function.call-user-func-array.php
        switch ($type) {
            case 3:
                //call class, method and pass parameter
                call_user_func_array(array($this->url_controller, $this->url_action), $this->url_params);
                break;
            case 2:
                //call class, method without parameter
                call_user_func(array($this->url_controller, $this->url_action));
                break;
            case 1:
                //call class with index, no method passed
                call_user_func(array($this->url_controller, 'index'));
                break;
            default:
                //call \App_mk0\Controllers\Error404()
                call_user_func(array($this->url_controller, 'index'));
                break;
        }
    }
}
