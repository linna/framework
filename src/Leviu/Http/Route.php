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

/**
 * Describe valid routes.
 * 
 */
class Route implements RouteInterface
{
    /**
     * @var int Indicates that route point at controller, method etc...
     */
    protected $typeOfRoute = null;

    
    protected $name = '';

   
    protected $method;

    protected $view = null;
            
    protected $model = null;
    
    protected $controller = null;

   
    protected $action = null;

    
    protected $param = array();

    
        
    /**
     * Route contructor.
     * 
     * @param string $name
     * @param string $method
     * @param string $controller
     * @param string $action
     * @param array  $param
     *
     */
    public function __construct($name, $method, $model, $view, $controller, $action, $param)
    {
        $this->name = $name;
        $this->method = $method;
        $this->model = $model;
        $this->view = $view;
        $this->controller = $controller;
        $this->action = $action;
        $this->param = $param;
        $this->typeOfRoute = $this->type();
    }

    /**
     * Type of route identifiend by a number
     * 1 for controller default method
     * 2 for controller with custom method
     * 3 for controller with custom method passing parameter
     * 
     * @return int Type of route
     *
     */
    protected function type()
    {
        $type = null;

        if ($this->controller !== null) {
            $type = 1;
        }

        if ($this->action !== null) {
            $type = 2;
        }

        if (sizeof($this->param) > 0) {
            $type = 3;
        }

        return $type;
    }

    /**
     * getType.
     * 
     * @return int Type of route
     *
     * @since 0.1.0
     */
    public function getType()
    {
        return $this->typeOfRoute;
    }

    /**
     * getController.
     * 
     * @return string Controller for call Controller->default_action()
     *
     * @since 0.1.0
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * getAction.
     * 
     * @return string Action for call Controller->action()
     *
     * @since 0.1.0
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * getParam.
     * 
     * @return array Action for call Controller->Action(Param)
     *
     * @since 0.1.0
     */
    public function getParam()
    {
        return $this->param;
    }
    
    public function getModel()
    {
        return $this->model;
    }
    
    public function getView()
    {
        return $this->view;
    }
}
