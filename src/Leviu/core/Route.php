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

namespace Leviu\Core;

/**
 * Route 
 * - Class for describe valid routes
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 */
class Route implements RouteInterface
{
    /**
     * @var int Indicates that route point at controller, method etc...
     */
    protected $typeOfRoute = null;
    
    /**
     * @var string Name of route
     * @todo implement reverse routing for utilize this properties
     */
    protected $name = '';
    
    /**
     * @var string Http method of route
     * @todo implement method control for utilize this properties
     */
    protected $method;
    
    /**
     * @var string Controller must be loaded
     * @since 0.1.0
     */
    protected $controller = null;
    
    /**
     * @var string Method must be loaded
     * @since 0.1.0
     */
    protected $action = null;
    
    /**
     * @var string Params must be passed to method
     * @since 0.1.0
     */
    protected $param = array();

    /**
     * Route contructor
     * 
     * @param string $name
     * @param string $method
     * @param string $controller
     * @param string $action
     * @param array $param
     * @since 0.1.0
     */
    public function __construct($name, $method, $controller, $action, $param)
    {
        $this->name = $name;
        $this->method = $method;
        $this->controller = $controller;
        $this->action = $action;
        $this->param = $param;
        $this->typeOfRoute = $this->type();
    }
    
    /**
     * type
     * 
     * Type of route identifiend by a number
     * 1 for controller default method
     * 2 for controller with custom method
     * 3 for controller with custom method passing parameter
     * 
     * @return int Type of route
     * @since 0.1.0
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
     * getType
     * 
     * @return int Type of route
     * @since 0.1.0
     */
    public function getType()
    {
        return $this->typeOfRoute;
    }
    
    /**
     * getController
     * 
     * @return string Controller for call Controller->default_action()
     * @since 0.1.0
     */
    public function getController()
    {
        return $this->controller;
    }
    
    /**
     * getAction
     * 
     * @return string Action for call Controller->action()
     * @since 0.1.0
     */
    public function getAction()
    {
        return $this->action;
    }
    
    /**
     * getParam
     * 
     * @return array Action for call Controller->Action(Param)
     * @since 0.1.0
     */
    public function getParam()
    {
        return $this->param;
    }
}
